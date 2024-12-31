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

            foreach( $request->precios as $precio ){

                $clienteHasProducto = ClienteHasProducto::where('idCliente', '=', $request->cliente)
                                    ->where('idProducto', '=', $precio['producto'])
                                    ->first();

                if( $clienteHasProducto ){

                    $clienteHasProducto->precio = $precio['precio'];
                    $clienteHasProducto->save();

                }else{

                    $clienteHasProducto = ClienteHasProducto::create([

                        'idCliente' => $request->cliente,
                        'idProducto' => $precio['producto'],
                        'precio' => $precio['precio'],

                    ]);

                }

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
