<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Http\Requests\Producto\Create;
use App\Http\Requests\Producto\Read;
use App\Http\Requests\Producto\Update;
use App\Http\Requests\Producto\Delete;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
        
            $productos = Producto::orderBy('created_at', 'desc')->get();

            return view('productos.index', compact('productos'));

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }
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
            
            $producto = Producto::create([

                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,

            ]);

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
    public function show(Read $request)
    {
        try {
            
            $producto = Producto::find( $request->id );

            if( $producto->id ){

                $datos['exito'] = true;
                $datos['nombre'] = $producto->nombre;
                $datos['descripcion'] = $producto->descripcion;
                $datos['id'] = $producto->id;

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
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request)
    {
        try {
            
            $producto = Producto::where('id', '=', $request->id)
                        ->update([

                            'nombre' => $request->nombre,
                            'descripcion' => $request->descripcion,

                        ]);

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
            
            $producto = Producto::find( $request->id );

            if( $producto->id ){

                $producto->delete();

                $datos['exito'] = true;

            }

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }
}
