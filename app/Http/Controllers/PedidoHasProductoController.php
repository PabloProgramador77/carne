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
    public function create( Request $request )
    {
        try {

            $total = 0;
            
            foreach( $request->pesos as $peso ){

                $cantidad = is_numeric( $peso['cantidad']) ? floatval( $peso['cantidad']) : round( floatval( $peso['cantidad']), 2);
                $precio = is_numeric( $peso['precio']) ? floatval( $peso['precio'] ) : round( floatval( $peso['precio']), 2);

                PedidoHasProducto::create([

                    'idPedido' => $request->pedido,
                    'idClienteHasProducto' => $peso['producto'],
                    'cantidad' => $cantidad,
                    'monto' => $cantidad * $precio,

                ]);

                $total += ($cantidad * $precio);

            }

            $pedidoController = new PedidoController();
            $pedidoController->edit( $request, $total );
            $clienteController = new ClienteController();
            $clienteController->edit( $request );

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
     * Store a newly created resource in storage.
     */
    public function store(Create $request)
    {
        try {

            $total = 0;
            
            foreach( $request->pesos as $peso ){

                $cantidad = is_numeric( $peso['cantidad']) ? floatval( $peso['cantidad']) : round( floatval( $peso['cantidad'] ) );
                $precio = is_numeric( $peso['precio']) ? floatval( $peso['precio'] ) : round( floatval( $peso['precio'] ) );

                PedidoHasProducto::create([

                    'idPedido' => $request->pedido,
                    'idClienteHasProducto' => $peso['producto'],
                    'cantidad' => $cantidad,
                    'monto' => $cantidad * $precio,

                ]);

                $total += ($cantidad * $precio);

            }

            $pedidoController = new PedidoController();
            $pedidoController->edit( $request, $total );
            $clienteController = new ClienteController();
            $clienteController->edit( $request );
            $pedidoController->create( $request->pedido );

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
