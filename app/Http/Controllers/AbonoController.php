<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Requests\Abono\Create;
use App\Http\Requests\Abono\Read;
use App\Http\Requests\Abono\Update;
use App\Http\Requests\Abono\Delete;
use App\Models\Pedido;
use NumberFormatter;
use \Mpdf\Mpdf;

class AbonoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( $id )
    {
        try {
            
            $abonos = Abono::where('idCliente', '=', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();

            $cliente = Cliente::find( $id );

            return view('abonos.index',compact('cliente', 'abonos'));

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            echo "Cliente no encontrado: ".$e->getMessage();

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( $idCliente, $idAbono )
    {
        try {
            
            $ticket = new \Mpdf\Mpdf([

                'mode' => 'utf-8',
                'format' => ['58', '2750'],
                'orientation' => 'P',
                'autoPageBreak' => false,
                'margin_left' => 5,
                'margin_right' => 5,
                'margin_top' => 5,
                'margin_bottom' => 5,
                'margin_header' => 5,
                'margin_footer' => 5,

            ]);

            $abono = Abono::find( $idAbono );
            $cliente = Cliente::find( $idCliente );

            $ticket->writeHTML('<h4 style="text-align: center;">Carniceria La Higienica</h4>');
            $ticket->writeHTML('<small style="text-align: center;">'.( auth()->user()->telefono ? : '' ).'</small>');
            $ticket->writeHTML('<small style="text-align: center;">'.( auth()->user()->direccion ? : '' ).'</small>');
            $ticket->writeHTML('<h6 style="text-align: center;"><b>Fecha:</b>'.$abono->updated_at.'</h6>');
            $ticket->writeHTML('<h5 style="text-align: center;"><b>Cliente:</b>'.$cliente->nombre.'</h5>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Cajero:</b></td><td>'.auth()->user()->name.'</td></tr>');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Folio:</b></td><td>'.$abono->id.'</td></tr>');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Concepto:</b></td><td>Abono</td></tr>');
            $ticket->WriteHTML('<tr><td style="font-size: 16px;"><b>Deuda:</b> $</td><td>'.floatval($cliente->deuda + $abono->monto).'</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><th>Nota</th><th>Importe</th></tr>');
            $ticket->writeHTML('<tr><td>'.$abono->nota.'</td><td>$'.$abono->monto.'</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<p style="text-align: center;"><b>Saldo de cuenta: $'.$cliente->deuda.'</b></p>');
            $ticket->writeHTML('<p style="text-align: center; margin-top: 20px;">_____________________</p>');
            $ticket->writeHTML('<p style="text-align: center;">Firma de '.$cliente->nombre.'</p>');

            $ticket->Output( public_path('tickets/').'abono'.$abono->id.'.pdf', \Mpdf\Output\Destination::FILE );

            $this->copia( $idCliente, $idAbono );

        } catch( \Mpdf\MpdfException $e){

            echo 'Error al generar el documento abono: '.$e->getMessage();

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Create $request)
    {
        try {
        
            $abono = Abono::create([

                'monto' => $request->monto,
                'nota' => $request->nota,
                'idCliente' => $request->cliente,

            ]);

            $idAbono = $abono->id;

            $cliente = Cliente::find( $request->cliente );

            if( $cliente && $cliente->id ){

                $deuda = is_numeric( $cliente->deuda) ? floatval( $cliente->deuda) : round( floatval( $cliente->deuda), 2);
                $monto = is_numeric( $request->monto) ? floatval( $request->monto) : round( floatval( $request->monto), 2);
                $deudaTotal = $deuda - $monto;

                $deuda = number_format( $deuda, 2 );

                Cliente::where('id', '=', $request->cliente )
                        ->update([

                            'deuda' => $deudaTotal,

                        ]);

                if( $request->pedidos && count( $request->pedidos ) > 0 ){

                    foreach( $request->pedidos as $pedido ){

                        Pedido::where('id', '=', $pedido)
                                ->update([

                                    'estado' => 'Pagado',

                                ]);

                    }

                }

                $this->create( $cliente->id, $idAbono );

                $datos['exito'] = true;

            }else{

                $datos['exito'] = false;
                $datos['mensaje'] = 'Cliente no encontrado';

            }

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
    public function show( Request $request )
    {
        try {
            
            $pedidos = Pedido::where('idCliente', '=', $request->cliente)
                    ->where('estado', '!=', 'Corte')
                    ->where('estado', '!=', 'Pagado')
                    ->get();

            if( count( $pedidos ) > 0 ){

                $datos['exito'] = true;
                $datos['pedidos'] = $pedidos;

            }else{

                $datos['exito'] = true;
                $datos['mensaje'] = 'Sin pedidos';

            }

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Abono $request)
    {
        try {
            

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request)
    {
        try {
            
            $abonoAnt = Abono::find( $request->id );

            $abono = Abono::where('id', '=', $request->id)
                    ->update([

                        'monto' => $request->monto,
                        'nota' => $request->nota,

                    ]);

            $idCliente = $abonoAnt->idCliente;

            $cliente = Cliente::find( $idCliente );

            if( $cliente && $cliente->id ){

                $deuda = is_numeric( $cliente->deuda) ? floatval( $cliente->deuda) : round( floatval( $cliente->deuda), 2);
                $monto = is_numeric( $request->monto) ? floatval( $request->monto) : round( floatval( $request->monto), 2);

                $deudaTotal = $deuda + $abonoAnt->monto;
                $deudaTotal = $deudaTotal - $monto; 

                $deuda = number_format( $deuda, 2 );

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

            $abono = Abono::find( $request->id );

            if( $abono->id ){

                $cliente = Cliente::find( $abono->idCliente );

                $deuda = is_numeric( $cliente->deuda) ? floatval( $cliente->deuda) : round( floatval( $cliente->deuda), 2);
                $monto = is_numeric( $abono->monto) ? floatval( $abono->monto) : round( floatval( $abono->monto), 2);

                $deudaTotal = $deuda + $monto;

                $deuda = $deudaTotal;
            
                $cliente = Cliente::where('id', '=', $abono->idCliente)
                        ->update([

                            'deuda' => $deudaTotal,

                        ]);

                $abono->delete();

                $datos['exito'] = true;

            }

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Abono no encontrado: '.$e->getMessage();

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }

    /**Copia de Ticket */
    public function copia( $idCliente, $idAbono ){
        try {
            
            $ticket = new \Mpdf\Mpdf([

                'mode' => 'utf-8',
                'format' => ['58', '2750'],
                'orientation' => 'P',
                'autoPageBreak' => false,
                'margin_left' => 5,
                'margin_right' => 5,
                'margin_top' => 5,
                'margin_bottom' => 5,
                'margin_header' => 5,
                'margin_footer' => 5,

            ]);

            $abono = Abono::find( $idAbono );
            $cliente = Cliente::find( $idCliente );

            $ticket->writeHTML('<h4 style="text-align: center;">Carniceria La Higienica</h4>');
            $ticket->writeHTML('<small style="text-align: center;">'.( auth()->user()->telefono ? : '' ).'</small>');
            $ticket->writeHTML('<small style="text-align: center;">'.( auth()->user()->direccion ? : '' ).'</small>');
            $ticket->writeHTML('<h6 style="text-align: center;"><b>Fecha:</b>'.$abono->updated_at.'</h6>');
            $ticket->writeHTML('<h5 style="text-align: center;"><b>Cliente:</b>'.$cliente->nombre.'</h5>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Cajero:</b></td><td>'.auth()->user()->name.'</td></tr>');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Folio:</b></td><td>'.$abono->id.'</td></tr>');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Concepto:</b></td><td>Abono</td></tr>');
            $ticket->WriteHTML('<tr><td style="font-size: 16px;"><b>Deuda:</b> $</td><td>'.floatval($cliente->deuda + $abono->monto).'</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><th>Nota</th><th>Importe</th></tr>');
            $ticket->writeHTML('<tr><td>'.$abono->nota.'</td><td>$'.$abono->monto.'</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<p style="text-align: center;"><b>Saldo de cuenta: $'.$cliente->deuda.'</b></p>');
            $ticket->writeHTML('<p style="text-align: center; margin-top: 10px;">**TICKET COPIA**</p>');

            $ticket->Output( public_path('tickets/').'copiaAbono'.$abono->id.'.pdf', \Mpdf\Output\Destination::FILE );

            if( file_exists( public_path('tickets/').'copiaAbono'.$abono->id.'.pdf' ) ){

                shell_exec('PDFtoPrinter.exe '.public_path('tickets/').'copiaAbono'.$abono->id.'.pdf "POS-58"');
                shell_exec('PDFtoPrinter.exe '.public_path('tickets/').'abono'.$abono->id.'.pdf "POS-58"');

            }

        } catch( \Mpdf\MpdfException $e){

            echo "Error al generar la copia del abono: ".$e->getMessage();

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }        
    }

    /**
     * Reimpresión de ticket
     */
    public function imprimir( Request $request ){
        try {
            
            $ticket = new \Mpdf\Mpdf([

                'mode' => 'utf-8',
                'format' => ['58', '2750'],
                'orientation' => 'P',
                'autoPageBreak' => false,
                'margin_left' => 5,
                'margin_right' => 5,
                'margin_top' => 5,
                'margin_bottom' => 5,
                'margin_header' => 5,
                'margin_footer' => 5,

            ]);

            $abono = Abono::find( $request->id );
            $cliente = Cliente::find( $abono->idCliente );

            $ticket->writeHTML('<h4 style="text-align: center;">Carniceria La Higienica</h4>');
            $ticket->writeHTML('<small style="text-align: center;">'.( auth()->user()->telefono ? : '' ).'</small>');
            $ticket->writeHTML('<small style="text-align: center;">'.( auth()->user()->direccion ? : '' ).'</small>');
            $ticket->writeHTML('<h6 style="text-align: center;"><b>Fecha:</b>'.$abono->updated_at.'</h6>');
            $ticket->writeHTML('<h5 style="text-align: center;"><b>Cliente:</b>'.$cliente->nombre.'</h5>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Cajero:</b></td><td>'.auth()->user()->name.'</td></tr>');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Folio:</b></td><td>'.$abono->id.'</td></tr>');
            $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Concepto:</b></td><td>Abono</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><th>Nota</th><th>Importe</th></tr>');
            $ticket->writeHTML('<tr><td style="font-size: 16px;">'.$abono->nota.'</td><td style="font-size: 16px;">$'.$abono->monto.'</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<p style="text-align: center;"><b>Saldo de cuenta: $'.$cliente->deuda.'</b></p>');
            $ticket->writeHTML('<p style="text-align: center; margin-top: 10px;">**TICKET REIMPRESO**</p>');

            $ticket->Output( public_path('tickets/').'reimpresionAbono'.$abono->id.'.pdf', \Mpdf\Output\Destination::FILE );

            if( file_exists( public_path('tickets/').'reimpresionAbono'.$abono->id.'.pdf' ) ){

                shell_exec('PDFtoPrinter.exe '.public_path('tickets/').'reimpresionAbono'.$abono->id.'.pdf "POS-58"');

                $datos['exito'] = true;

            }

        } catch( \Mpdf\MpdfException $e ){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Error al reimprimir el abono: '.$e->getMessage();

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }
        
        return response()->json( $datos );
    }
}
