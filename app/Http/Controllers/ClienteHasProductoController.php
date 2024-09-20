<?php

namespace App\Http\Controllers;

use App\Models\ClienteHasProducto;
use Illuminate\Http\Request;
use App\Http\Requests\ClienteHasProducto\Create;

class ClienteHasProductoController extends Controller
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

            $clienteHasProducto = ClienteHasProducto::where('idCliente', '=', $request->cliente)->get();

                if( count( $clienteHasProducto ) > 0 ){

                    foreach( $clienteHasProducto as $producto ){

                        $producto->delete();

                    }

                }

            foreach( $request->precios as $precio ){

                $clienteHasProducto = ClienteHasProducto::create([

                    'idCliente' => $request->cliente,
                    'idProducto' => $precio['producto'],
                    'precio' => $precio['precio'],

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
     * Display the specified resource.
     */
    public function show(ClienteHasProducto $clienteHasProducto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClienteHasProducto $clienteHasProducto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClienteHasProducto $clienteHasProducto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClienteHasProducto $clienteHasProducto)
    {
        //
    }
}
