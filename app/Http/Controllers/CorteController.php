<?php

namespace App\Http\Controllers;

use App\Models\Corte;
use App\Models\CorteHasPedido;
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

            if( count( $pedidos ) > 0 ){

                $datos['exito'] = true;
                $datos['pedidos'] = $pedidos;

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
            
            if( count( $pedidos ) > 0 ){

                $total = 0;
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

                }

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

            if( count( $pedidos ) > 0 ){

                $datos['exito'] = true;
                $datos['pedidos'] = $pedidos;

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
}
