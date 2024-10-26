<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Corte;
use App\Models\CorteHasGasto;
use App\Models\CorteHasPedido;
use App\Models\Gasto;
use App\Models\Pedido;
use Illuminate\Http\Request;

class CorteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            
            $cortes = Corte::orderBy('created_at', 'desc')->get();

            return view('cortes.index', compact('cortes'));

        } catch (\Throwable $th) {
            
            echo $th->getMessage();
            
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            
            $pedidos = Pedido::select('pedidos.total', 'pedidos.created_at', 'clientes.nombre', 'pedidos.estado')
                        ->join('clientes', 'pedidos.idCliente', '=', 'clientes.id')
                        ->where('pedidos.estado', '!=', 'Corte')
                        ->get();

            $gastos = Gasto::select('gastos.id', 'gastos.monto', 'gastos.descripcion', 'gastos.created_at')
                    ->where('gastos.estado', '!=', 'Corte')
                    ->get();

            if( count( $pedidos ) > 0 || count( $gastos ) > 0 ){

                $datos['exito'] = true;
                $datos['pedidos'] = $pedidos;
                $datos['gastos'] = $gastos;

            }else{

                $datos['exito'] = true;
                $datos['pedidos'] = [];

            }

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            
            $pedidos = Pedido::select('pedidos.id', 'pedidos.total', 'pedidos.created_at', 'clientes.nombre', 'pedidos.estado')
                        ->join('clientes', 'pedidos.idCliente', '=', 'clientes.id')
                        ->where('pedidos.estado', '!=','Corte')
                        ->get();

            $gastos = Gasto::select('gastos.id', 'gastos.monto', 'gastos.descripcion', 'gastos.estado')
                    ->where('gastos.estado', '!=', 'Corte')
                    ->get();
            
            if( count( $pedidos ) > 0 || count( $gastos ) > 0 ){

                $total = 0;
                $costos = 0;
                $efectivo = 0;

                foreach( $pedidos as $pedido ){

                    if( $pedido->estado === 'Pagado' ){

                        $efectivo += $pedido->total;

                        $orden = Pedido::where('id', '=', $pedido->id)
                                ->update([
                                    
                                    'estado' => 'Corte',
                                    
                                ]);

                    }

                    $total += $pedido->total;

                }

                foreach( $gastos as $gasto ){

                    $efectivo -= $gasto->monto;

                    $costo = Gasto::where('id', '=', $gasto->id)
                            ->update([

                                'estado' => 'Corte',

                            ]);

                    $costos += $gasto->monto;

                }

                $corte = Corte::create([

                    'total' => $total,
                    'efectivo' => $efectivo,

                ]);

                if( $corte->id ){

                    $idCorte = $corte->id;

                    foreach( $pedidos as $pedido ){

                        if( $pedido->estado === 'Pagado' ){

                            $corteHasPedido = CorteHasPedido::create([

                                'idCorte' => $idCorte,
                                'idPedido' => $pedido->id,
    
                            ]);

                        }

                    }

                    foreach( $gastos as $gasto){

                        $corteHasGasto = CorteHasGasto::create([

                            'idCorte' => $idCorte,
                            'idGasto' => $gasto->id,

                        ]);

                    }

                }

                $caja = Caja::find( $request->caja );

                $totalCaja = floatval( ( ($caja->total + $efectivo) - $costos ) );

                Caja::where('id', '=', $request->caja)
                        ->update([

                            'total' => $totalCaja,

                        ]);


                $this->corte( $idCorte );

                $datos['exito'] = true;

            }

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
            
            $pedidos = Pedido::select('pedidos.id', 'pedidos.total', 'pedidos.created_at', 'clientes.nombre')
                        ->join('clientes', 'pedidos.idCliente', '=', 'clientes.id')
                        ->join('corte_has_pedidos', 'pedidos.id', '=', 'corte_has_pedidos.idPedido')
                        ->where('corte_has_pedidos.idCorte', '=', $request->id)
                        ->orderBy('pedidos.created_at', 'desc')
                        ->get();

            $gastos = Gasto::select('gastos.id', 'gastos.monto', 'gastos.descripcion','gastos.created_at')
                    ->join('corte_has_gastos', 'gastos.id', '=', 'corte_has_gastos.idGasto')
                    ->where('corte_has_gastos.idCorte', '=', $request->id)
                    ->get();


            if( count( $pedidos ) > 0 || count( $gastos ) > 0 ){

                $datos['exito'] = true;
                $datos['pedidos'] = $pedidos;
                $datos['gastos'] = $gastos;

            }else{

                $datos['exito'] = true;
                $datos['pedidos'] = [];

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
    public function edit(Corte $corte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Corte $corte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Corte $corte)
    {
        //
    }

    /**
     * Reporte PDF de corte
     */
    public function corte( $idCorte ){
        try {
            
            $corte = Corte::find( $idCorte );

            if( $corte && $corte->id ){

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

                $ticket->writeHTML('<h4 style="text-align: center;">Carniceria La Higienica</h4>');
                $ticket->writeHTML('<h6 style="text-align: center;"><b>Fecha:</b>'.$corte->created_at.'</h6>');
                $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
                $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Folio:</b></td><td>'.$corte->id.'</td></tr>');
                $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Cajero:</b></td><td>'.auth()->user()->name.'</td></tr>');
                $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Concepto:</b> </td><td>Corte de caja</td></tr>');
                $ticket->writeHTML('</table>');

                $pedidos = Pedido::select('pedidos.total', 'pedidos.created_at', 'clientes.nombre')
                        ->join('clientes', 'pedidos.idCliente', '=', 'clientes.id')
                        ->join('corte_has_pedidos', 'pedidos.id', '=', 'corte_has_pedidos.idPedido')
                        ->where('corte_has_pedidos.idCorte', '=', $corte->id)
                        ->get();

                if( count( $pedidos ) > 0 ){

                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
                    $ticket->writeHTML('<tr><th>Pedido</th><th>Importe</th></tr>');

                    foreach( $pedidos as $pedido ){

                        $ticket->writeHTML('<tr><td style="font-size: 14px;">'.$pedido->nombre.'</td><td style="font-size: 14px;">$ '.$pedido->total.'</td></tr>');

                    }

                    $ticket->writeHTML('<tr><td colpsan="2" style="text-align: center;"><b>Total de Corte: $ '.$corte->total.'</b></td></tr>');
                    $ticket->writeHTML('</table>');

                }else{

                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
                    $ticket->writeHTML('<tr><th>Pedido</th><th>Importe</th></tr>');
                    $ticket->writeHTML('<tr><td colspan="3" style="text-align: center;">Sin pedidos en el corte</td></tr>');
                    $ticket->writeHTML('</table>');

                }
                
                $ticket->Output( public_path('tickets/').'corte'.$corte->id.'.pdf', \Mpdf\Output\Destination::FILE );

                if( file_exists( public_path('tickets/').'corte'.$corte->id.'.pdf' ) ){

                    shell_exec('PDFtoPrinter.exe '.public_path('tickets/').'corte'.$corte->id.'.pdf "POS-58 11.3.0.1"');
                     
                }

            }

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }
    }
}
