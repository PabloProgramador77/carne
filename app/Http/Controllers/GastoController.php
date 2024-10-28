<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use App\Models\Caja;
use Illuminate\Http\Request;
use App\Http\Requests\Gasto\Create;
use App\Http\Requests\Gasto\Read;
use App\Http\Requests\Gasto\Update;
use App\Http\Requests\Gasto\Delete;
use App\Http\Controllers\CajaController;

class GastoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( $id )
    {
        try {

            $caja = Caja::find( $id );
            $gastos = Gasto::where('estado', '!=', 'Corte')
                    ->get();

            return view('gastos.index', compact('caja', 'gastos'));
            
        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            echo "Caja\Gasto(s) no encontrados: ".$e->getMessage();

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
            
            $gasto = Gasto::create([

                'monto' => $request->monto,
                'descripcion' => $request->descripcion,
                'estado' => 'Pendiente',
                'idCaja' => $request->caja,

            ]);

            $datos['exito'] = true;

        }catch( \Illuminate\Validation\ValidationException $e ){

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
    public function show(Gasto $gasto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gasto $gasto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request)
    {
        try {
            
            $gasto = Gasto::where('id', '=', $request->id)
                    ->update([

                        'monto' => $request->monto,
                        'descripcion' => $request->descripcion,

                    ]);

            $datos['exito'] = true;

        }catch( \Illuminate\Validation\ValidationException $e ){

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
            
            $gasto = Gasto::find( $request->id );

            if( $gasto->id ){

                $gasto->delete();

                $datos['exito'] = true;

            }else{

                $datos['exito'] = false;
                $datos['mensaje'] = 'Gasto no encontrado';
                
            }

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Gasto no encontrado: '.$e->getMessage();

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }
}
