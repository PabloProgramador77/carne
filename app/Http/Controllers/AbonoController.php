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
                'format' => ['80', '2750'],
                'orientation' => 'P',
                'autoPageBreak' => false,

            ]);

            $abono = Abono::find( $idAbono );
            $cliente = Cliente::find( $idCliente );

            $ticket->writeHTML('<h4 style="text-align: center;">La Higienica Premium</h4>');
            $ticket->writeHTML('<h4 style="text-align: center;">4765876390</h4>');
            $ticket->writeHTML('<h5 style="text-align: center;">'.$abono->updated_at.'</h5>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><td>Cajero:</td><td>'.auth()->user()->name.'</td></tr>');
            $ticket->writeHTML('<tr><td>Folio:</td><td>'.$abono->id.'</td></tr>');
            $ticket->writeHTML('<tr><td>Cliente:</td><td>'.$cliente->nombre.'</td></tr>');
            $ticket->writeHTML('<tr><td>Concepto:</td><td>Abono</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><th>Nota</th><th>Importe</th></tr>');
            $ticket->writeHTML('<tr><td>'.$abono->nota.'</td><td>$'.$abono->monto.'</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<p style="text-align: center;"><b>Saldo de cuenta:</b> $'.$cliente->deuda.'</p>');
            $ticket->writeHTML('<p style="text-align: center; margin-top: 20px;">_____________________</p>');
            $ticket->writeHTML('<p style="text-align: center;">Firma de '.$cliente->nombre.'</p>');

            $ticket->Output( public_path('tickets/').'abono'.$abono->id.'.pdf', \Mpdf\Output\Destination::FILE );

            $this->copia( $idCliente, $idAbono );

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

                $this->create( $cliente->id, $idAbono );

            }

            $datos['exito'] = true;

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }

    /**
     * Display the specified resource.
     */
    public function show(Abono $abono)
    {
        //
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
                'format' => ['80', '2750'],
                'orientation' => 'P',
                'autoPageBreak' => false,

            ]);

            $abono = Abono::find( $idAbono );
            $cliente = Cliente::find( $idCliente );

            $ticket->writeHTML('<h4 style="text-align: center;">La Higienica Premium</h4>');
            $ticket->writeHTML('<h4 style="text-align: center; 4765876390"></h4>');
            $ticket->writeHTML('<h5 style="text-align: center;">'.$abono->updated_at.'</h5>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><td>Cajero:</td><td>'.auth()->user()->name.'</td></tr>');
            $ticket->writeHTML('<tr><td>Folio:</td><td>'.$abono->id.'</td></tr>');
            $ticket->writeHTML('<tr><td>Cliente:</td><td>'.$cliente->nombre.'</td></tr>');
            $ticket->writeHTML('<tr><td>Concepto:</td><td>Abono</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
            $ticket->writeHTML('<tr><th>Nota</th><th>Importe</th></tr>');
            $ticket->writeHTML('<tr><td>'.$abono->nota.'</td><td>$'.$abono->monto.'</td></tr>');
            $ticket->writeHTML('</table>');
            $ticket->writeHTML('<p style="text-align: center;"><b>Saldo de cuenta:</b> $'.$cliente->deuda.'</p>');
            $ticket->writeHTML('<p style="text-align: center; margin-top: 10px;">**TICKET COPIA**</p>');

            $ticket->Output( public_path('tickets/').'copiaAbono'.$abono->id.'.pdf', \Mpdf\Output\Destination::FILE );

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }        
    }
}
