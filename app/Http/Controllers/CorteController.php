<?php

namespace App\Http\Controllers;

use App\Models\Abono;
use App\Models\Caja;
use App\Models\Corte;
use App\Models\CorteHasAbono;
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

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            echo "Cortes no encontrados: ".$e->getMessage();

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

            $abonos = Abono::select('abonos.id', 'abonos.monto', 'abonos.nota', 'abonos.created_at')
                    ->where('abonos.estado', '!=', 'Corte')
                    ->get();

            if( count( $pedidos ) > 0 || count( $gastos ) > 0 || count( $abonos ) > 0 ){

                $datos['exito'] = true;
                $datos['pedidos'] = $pedidos;
                $datos['gastos'] = $gastos;
                $datos['abonos'] = $abonos;

            }else{

                $datos['exito'] = true;
                $datos['pedidos'] = [];

            }

        } catch( \Illuminate\Validation\ValidationException $e ){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Error de validación: '.$e->getMessage();

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

            $abonos = Abono::select('abonos.id', 'abonos.monto', 'abonos.nota', 'abonos.estado')
                    ->where('abonos.estado', '!=', 'Corte')
                    ->get();

            $total = 0;
            $costos = 0;
            $abonado = 0;
            $efectivo = 0;

            if( count( $pedidos ) > 0 ){

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

            }
            
            if( count( $gastos ) > 0 ){

                foreach( $gastos as $gasto ){

                    $efectivo -= $gasto->monto;
    
                    $costo = Gasto::where('id', '=', $gasto->id)
                            ->update([
    
                                'estado' => 'Corte',
    
                            ]);
    
                    $costos += $gasto->monto;
    
                }

            }
            
            if( count( $abonos ) > 0 ){

                foreach( $abonos as $abono ){

                    $efectivo += $abono->monto;
    
                    Abono::where('id', '=', $abono->id)
                            ->update([
    
                                'estado' => 'Corte',
    
                            ]);
                    
                    $abonado += $abono->monto;
    
                }

            }

            $caja = Caja::find( $request->caja );

            $totalCaja = floatval( ( ( $caja->apertura + $efectivo + $abonado ) - $costos ) );

            $corte = Corte::create([

                'total' => $totalCaja,
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

                foreach( $abonos as $abono ){

                    $corteHasAbono = CorteHasAbono::create([

                        'idCorte' => $idCorte,
                        'idAbono' => $abono->id,

                    ]);

                }

            }

            Caja::where('id', '=', $request->caja)
                    ->update([

                        'total' => $totalCaja,

                    ]);


            $this->corte( $idCorte, $caja->apertura );

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

            $abonos = Abono::select('abonos.id', 'abonos.monto', 'abonos.nota', 'abonos.created_at')
                    ->join('corte_has_abonos', 'abonos.id', '=', 'corte_has_abonos.idAbono')
                    ->where('corte_has_abonos.idCorte', '=', $request->id)
                    ->get();


            if( count( $pedidos ) > 0 || count( $gastos ) > 0 || count( $abonos ) > 0 ){

                $datos['exito'] = true;
                $datos['pedidos'] = $pedidos;
                $datos['gastos'] = $gastos;
                $datos['abonos'] = $abonos;

            }else{

                $datos['exito'] = true;
                $datos['pedidos'] = [];

            }

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Gasto\Pedido(s) no encontrado(s): '.$e->getMessage();

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
    public function corte( $idCorte, $apertura ){
        try {
            
            $corte = Corte::find( $idCorte );

            if( $corte && $corte->id ){

                $totalPedidos = 0;
                $totalGastos = 0;
                $totalAbonos = 0;

                $ticket = new \Mpdf\Mpdf([

                    'mode' => 'utf-8',
                    'format' => ['50', '2750'],
                    'orientation' => 'P',
                    'autoPageBreak' => false,
                    'margin_left' => 0,
                    'margin_right' => 0,
                    'margin_top' => 5,
                    'margin_bottom' => 5,
    
                ]);

                $pedidos = Pedido::select('pedidos.total')
                        ->join('corte_has_pedidos', 'pedidos.id', '=', 'corte_has_pedidos.idPedido')
                        ->where('corte_has_pedidos.idCorte', '=', $corte->id)
                        ->get();

                $gastos = Gasto::select('gastos.monto')
                        ->join('corte_has_gastos', 'gastos.id', '=', 'corte_has_gastos.idGasto')
                        ->where('corte_has_gastos.idCorte', '=', $corte->id)
                        ->get();

                $abonos = Abono::select('abonos.monto')
                        ->join('corte_has_abonos', 'abonos.id', '=', 'corte_has_abonos.idAbono')
                        ->where('corte_has_abonos.idCorte', '=', $corte->id)
                        ->get();

                if( count( $pedidos ) > 0 ){

                    foreach( $pedidos as $pedido ){

                        $totalPedidos += $pedido->total;

                    }

                }

                if( count( $gastos ) > 0 ){

                    foreach( $gastos as $gasto ){

                        $totalGastos += $gasto->monto;

                    }
                    
                }

                if( count( $abonos ) > 0 ){
                    
                    foreach( $abonos as $abono ){

                        $totalAbonos += $abono->monto;

                    }

                }

                $ticket->writeHTML('<h4 style="text-align: center;">Carniceria La Higienica</h4>');
                $ticket->writeHTML('<p style="text-align: center; display: block; width: 100%;">'.( auth()->user()->telefono ? : '' ).'</p>');
                $ticket->writeHTML('<p style="text-align: center; display: block; width: 100%;">'.( auth()->user()->direccion ? : '' ).'</p>');
                $ticket->writeHTML('<h6 style="text-align: center;"><b>Fecha:</b>'.$corte->created_at->format('d/m/y g:i A').'</h6>');
                $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
                $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Folio:</b></td><td>'.$corte->id.'</td></tr>');
                $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Cajero:</b></td><td>'.auth()->user()->name.'</td></tr>');
                $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Concepto:</b> </td><td>Corte de caja</td></tr>');
                $ticket->writeHTML('</table>');
                $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
                $ticket->writeHTML('<tr><td style="text-align: center;"><b>Importe de apertura: $ '.$apertura.'</b></td></tr>');
                $ticket->writeHTML('<tr><td style="text-align: center;"><b>Total de Pedidos: $ '.$totalPedidos.'</b></td></tr>');
                $ticket->writeHTML('<tr><td style="text-align: center;"><b>Total de Gastos: $ '.$totalGastos.'</b></td></tr>');
                $ticket->writeHTML('<tr><td style="text-align: center;"><b>Total de Abonos: $ '.$totalAbonos.'</b></td></tr>');
                $ticket->writeHTML('<tr><td style="text-align: center;"><b>Total de Corte: $ '.($corte->efectivo + $apertura).'</b></td></tr>');
                $ticket->writeHTML('</table>');
                
                $ticket->Output( public_path('tickets/').'corte'.$corte->id.'.pdf', \Mpdf\Output\Destination::FILE );

                if( file_exists( public_path('tickets/').'corte'.$corte->id.'.pdf' ) ){

                    shell_exec('PDFtoPrinter.exe '.public_path('tickets/').'corte'.$corte->id.'.pdf "POS-58"');
                     
                }

            }

        } catch( \Mpdf\MpdfException $e ){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Error al generar el corte: '.$e->getMessage();

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }
    }
}
