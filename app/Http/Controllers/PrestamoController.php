<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\Prestamo\Create;
use App\Http\Requests\Prestamo\Read;
use App\Http\Requests\Prestamo\update;
use App\Http\Requests\Prestamo\Delete;
use \Mpdf\Mpdf;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( $id )
    {
        try {
            
            $prestamos = Prestamo::where('idCliente', '=', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();

            $cliente = Cliente::find( $id );

            return view('prestamos.index',compact('cliente', 'prestamos'));

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            echo "Prestamo no encontrado: ".$e->getMessage();

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( $idCliente, $idPrestamo )
    {
        try{

            $ticket = new \Mpdf\Mpdf([

                'mode' => 'utf-8',
                'format' => ['58', '2750'],
                'orientation' => 'P',
                'autoPageBreak' => false,
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 5,
                'margin_bottom' => 5,

            ]);

            $prestamo = Prestamo::find( $idPrestamo );
            $cliente = Cliente::find( $idCliente );

            $ticket->writeHTML('<h4 style="text-align: center;">Carniceria La Higienica</h4>');
            $ticket->writeHTML('<p style="text-align: center; display: block; width: 100%;">'.( auth()->user()->telefono ? : '' ).'</p>');
            $ticket->writeHTML('<p style="text-align: center; display: block; width: 100%;">'.( auth()->user()->direccion ? : '' ).'</p>');
            $ticket->writeHTML('<h6 style="text-align: center;"><b>Fecha:</b>'.$prestamo->created_at->format('dd/mm/yy g:i A').'</h6>');
            $ticket->writeHTML('<h5 style="text-align: center;"><b>Cliente:</b>'.$cliente->nombre.'</h5>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Cajero:</b></td><td>'.auth()->user()->name.'</td></tr>');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Folio:</b></td><td>'.$prestamo->id.'</td></tr>');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Concepto:</b></td><td>Prestamo</td></tr>');
            $ticket->WriteHTML('<tr><td style="font-size: 16px;"><b>Deuda: </b> $</td><td>'.floatval($cliente->deuda - $prestamo->monto).'</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><th>Nota</th><th>Importe</th></tr>');
            $ticket->writeHTML('<tr><td>'.$prestamo->nota.'</td><td>$'.$prestamo->monto.'</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<p style="text-align: center;"><b>Saldo de cuenta: $'.$cliente->deuda.'</b></p>');
            $ticket->writeHTML('<p style="text-align: center; margin-top: 20px;">_____________________</p>');
            $ticket->writeHTML('<p style="text-align: center;">Firma de '.$cliente->nombre.'</p>');

            $ticket->Output( public_path('tickets/').'prestamo'.$prestamo->id.'.pdf', \Mpdf\Output\Destination::FILE );

            $this->copia( $idCliente, $idPrestamo );

        } catch( \Mpdf\MpdfException $e){

            echo 'Error al generar el documento de prestamo: '.$e->getMessage();

        } catch(\Throwable $th){

            echo $th->getMessage();

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Create $request)
    {
        try {
        
            $prestamo = Prestamo::create([

                'monto' => $request->monto,
                'nota' => $request->nota,
                'idCliente' => $request->cliente,

            ]);

            $idPrestamo = $prestamo->id;

            $cliente = Cliente::find( $request->cliente );

            if( $cliente && $cliente->id ){

                $deuda = is_numeric( $cliente->deuda) ? floatval( $cliente->deuda) : round( floatval( $cliente->deuda), 2);
                $monto = is_numeric( $request->monto) ? floatval( $request->monto) : round( floatval( $request->monto), 2);

                $deudaTotal = $deuda + $monto;

                Cliente::where('id', '=', $request->cliente )
                        ->update([

                            'deuda' => $deudaTotal,

                        ]);

            }

            $datos['exito'] = true;

        } catch( \Illuminate\Validation\ValidationException $e ){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Error de validación: '.$e->getMessage();

        } catch( \Illuminate\Database\QueryException $e){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Error en la base de datos: '.$e->getMessage();

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }

    /**
     * Display the specified resource.
     */
    public function show(Prestamo $prestamo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prestamo $prestamo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request)
    {
        try {
            
            $prestamoAnt = Prestamo::find( $request->id );

            $prestamo = Prestamo::where('id', '=', $request->id)
                    ->update([

                        'monto' => $request->monto,
                        'nota' => $request->nota,

                    ]);

            $idCliente = $prestamoAnt->idCliente;

            $cliente = Cliente::find( $idCliente );

            if( $cliente && $cliente->id ){

                $deuda = is_numeric( $cliente->deuda) ? floatval( $cliente->deuda) : round( floatval( $cliente->deuda), 2);
                $monto = is_numeric( $request->monto) ? floatval( $request->monto) : round( floatval( $request->monto), 2);

                $deudaTotal = $deuda - $prestamoAnt->monto;

                $deudaTotal = $deudaTotal + $request->monto;

                $cliente = Cliente::where('id', '=', $idCliente)
                        ->update([

                            'deuda' => $deudaTotal,

                        ]);

            }

            $datos['exito'] = true;

        } catch( \Illuminate\Validation\ValidationException $e ){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Error de validación: '.$e->getMessage();

        } catch( \Illuminate\Database\QueryException $e){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Error en la base de datos: '.$e->getMessage();

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delete $request)
    {
        try {

            $prestamo = Prestamo::find( $request->id );

            if( $prestamo->id ){

                $cliente = Cliente::find( $prestamo->idCliente );

                $deuda = is_numeric( $cliente->deuda) ? floatval( $cliente->deuda) : round( floatval( $cliente->deuda), 2);
                $monto = is_numeric( $prestamo->monto) ? floatval( $prestamo->monto) : round( floatval( $prestamo->monto), 2);

                $deudaTotal = $deuda - $monto;
            
                $cliente = Cliente::where('id', '=', $prestamo->idCliente)
                        ->update([

                            'deuda' => $deudaTotal,

                        ]);

                $prestamo->delete();

                $datos['exito'] = true;

            }else{

                $datos['exito'] = false;
                $datos['mensaje'] = 'Prestamo no encontrado';

            }

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Prestamo no encontrado: '.$e->getMessage();

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }

    /**
     * Copia de ticket
     */
    public function copia( $idCliente, $idPrestamo ){
        try{

            $ticket = new \Mpdf\Mpdf([

                'mode' => 'utf-8',
                'format' => ['58', '2750'],
                'orientation' => 'P',
                'autoPageBreak' => false,
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 5,
                'margin_bottom' => 5,

            ]);

            $prestamo = Prestamo::find( $idPrestamo );
            $cliente = Cliente::find( $idCliente);

            $ticket->writeHTML('<h4 style="text-align: center;">Carniceria La Higienica</h4>');
            $ticket->writeHTML('<p style="text-align: center; display: block; width: 100%;">'.( auth()->user()->telefono ? : '' ).'</p>');
            $ticket->writeHTML('<p style="text-align: center; display: block; width: 100%;">'.( auth()->user()->direccion ? : '' ).'</p>');
            $ticket->writeHTML('<h6 style="text-align: center;"><b>Fecha:</b>'.$prestamo->created_at->format('dd/mm/yy g:i A').'</h6>');
            $ticket->writeHTML('<h5 style="text-align: center;"><b>Cliente:</b>'.$cliente->nombre.'</h5>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Cajero:</b></td><td>'.auth()->user()->name.'</td></tr>');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Folio:</b></td><td>'.$prestamo->id.'</td></tr>');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Concepto:</b></td><td>Prestamo</td></tr>');
            $ticket->WriteHTML('<tr><td style="font-size: 16px;"><b>Deuda:</b> $</td><td>'.floatval($cliente->deuda - $prestamo->monto).'</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><th>Nota</th><th>Importe</th></tr>');
            $ticket->writeHTML('<tr><td>'.$prestamo->nota.'</td><td>$'.$prestamo->monto.'</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<p style="text-align: center;"><b>Saldo de cuenta: $'.$cliente->deuda.'</b></p>');
            $ticket->writeHTML('<p style="text-align: center; margin-top: 10px;">**TICKET COPIA**</p>');

            $ticket->Output( public_path('tickets/').'copiaPrestamo'.$prestamo->id.'.pdf', \Mpdf\Output\Destination::FILE );

            if( file_exists( public_path('tickets/').'copiaPrestamo'.$prestamo->id.'.pdf' ) ){

                shell_exec('PDFtoPrinter.exe '.public_path('tickets/').'copiaPrestamo'.$prestamo->id.'.pdf "POS-58"');
                shell_exec('PDFtoPrinter.exe '.public_path('tickets/').'prestamo'.$prestamo->id.'.pdf "POS-58"');

            }

        } catch( \Mpdf\MpdfException $e){

            echo "Error al generar la copia del prestamo: ".$e->getMessage();

        } catch(\Throwable $th){

            echo $th->getMessage();

        }        
    }

    /**
     * Reimpresión de ticket
     */
    public function imprimir( Request $request ){
        try{

            $ticket = new \Mpdf\Mpdf([

                'mode' => 'utf-8',
                'format' => ['58', '2750'],
                'orientation' => 'P',
                'autoPageBreak' => false,
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 5,
                'margin_bottom' => 5,

            ]);

            $prestamo = Prestamo::find( $request->id );
            $cliente = Cliente::find( $prestamo->idCliente );

            $ticket->writeHTML('<h4 style="text-align: center;">Carniceria La Higienica</h4>');
            $ticket->writeHTML('<p style="text-align: center; display: block; width: 100%;">'.( auth()->user()->telefono ? : '' ).'</p>');
            $ticket->writeHTML('<p style="text-align: center; display: block; width: 100%;">'.( auth()->user()->direccion ? : '' ).'</p>');
            $ticket->writeHTML('<h6 style="text-align: center;"><b>Fecha:</b>'.$prestamo->created_at->format('dd/mm/yy g:i A').'</h6>');
            $ticket->writeHTML('<h5 style="text-align: center;"><b>Cliente:</b>'.$cliente->nombre.'</h5>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Cajero:</b></td><td>'.auth()->user()->name.'</td></tr>');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Folio:</b></td><td>'.$prestamo->id.'</td></tr>');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Concepto:</b></td><td>Prestamo</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><th>Nota</th><th>Importe</th></tr>');
            $ticket->writeHTML('<tr><td>'.$prestamo->nota.'</td><td>$'.$prestamo->monto.'</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<p style="text-align: center;"><b>Saldo de cuenta: $'.$cliente->deuda.'</b></p>');
            $ticket->writeHTML('<p style="text-align: center; margin-top: 10px;">**TICKET REIMPRESO**</p>');

            $ticket->Output( public_path('tickets/').'reimpresionPrestamo'.$prestamo->id.'.pdf', \Mpdf\Output\Destination::FILE );

            if( file_exists( public_path('tickets/').'reimpresionPrestamo'.$prestamo->id.'.pdf' ) ){

                shell_exec('PDFtoPrinter.exe '.public_path('tickets/').'reimpresionPrestamo'.$prestamo->id.'.pdf "POS-58"');

                $datos['exito'] = true;

            }

        } catch( \Mpdf\MpdfException $e){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Error al reimprimir el prestamo: '.$e->getMessage();

        } catch(\Throwable $th){

            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }
}
