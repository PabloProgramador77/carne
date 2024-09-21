<?php

namespace App\Http\Controllers;

use App\Models\PedidoHasProducto;
use Illuminate\Http\Request;
use App\Http\Requests\PedidoHasProducto\Create;
use Mpdf\Mpdf;

class PedidoHasProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Create $request)
    {
        try {

            $total = 0;
            
            foreach( $request->pesos as $peso ){

                $pedidoHasProducto = PedidoHasProducto::create([

                    'idPedido' => $request->pedido,
                    'idClienteHasProducto' => $peso['producto'],
                    'cantidad' => $peso['cantidad'],
                    'monto' => number_format( $peso['cantidad'] * $peso['precio'] ),

                ]);

                $total += number_format( $peso['cantidad'] * $peso['precio'] );

            }

            $pedidoController = new PedidoController();
            $pedidoController->edit( $request, $total );
            $pedidoController->create( $request->pedido );

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
    public function show(PedidoHasProducto $pedidoHasProducto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PedidoHasProducto $pedidoHasProducto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PedidoHasProducto $pedidoHasProducto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PedidoHasProducto $pedidoHasProducto)
    {
        //
    }
}
