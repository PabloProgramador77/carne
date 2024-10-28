<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use Illuminate\Http\Request;
use App\Http\Requests\Caja\Create;
use App\Http\Requests\Caja\Read;
use App\Http\Requests\Caja\Update;
use App\Http\Requests\Caja\Delete;
use App\Http\Requests\Gasto\Importe;

class CajaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            
            $cajas = Caja::all();

            return view('cajas.index', compact('cajas'));

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            echo "Cajas no encontradas: ".$e->getMessage();

        } catch (\Throwable $th) {
            
            echo 'Error al obtener las cajas: '.$th->getMessage();

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
            
            $caja = Caja::create([

                'nombre' => $request->nombre,
                'total' => 0,

            ]);

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
    public function show(Caja $caja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Importe $request)
    {
        try {
            
            $caja = Caja::where('id', '=', $request->caja)
                    ->update([

                        'total' => $request->importe,

                    ]);

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
     * Update the specified resource in storage.
     */
    public function update(Update $request)
    {
        try {
            
            $caja = Caja::where('id', '=', $request->id)
                    ->update([

                        'nombre' => $request->nombre,

                    ]);
                
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
            
            $caja = Caja::find( $request->id );

            if( $caja->id ){

                $caja->delete();

                $datos['exito'] = true;

            }else{

                $datos['exito'] = false;
                $datos['mensaje'] = 'Caja no encontrada';

            }

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Caja no encontrada: '.$e->getMessage();

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }
}
