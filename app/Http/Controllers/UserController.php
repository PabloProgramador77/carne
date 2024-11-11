<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Usuario\Create;
use App\Http\Requests\Usuario\Delete;
use App\Http\Requests\Usuario\Update;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            
            $usuarios = User::all();
            $roles = Role::all();

            return view('usuarios.index', compact('usuarios', 'roles'));

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            echo "Uuarios no encontrados: ".$e->getMessage();

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
            
            return view('usuarios.perfil');

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Create $request)
    {
        try {
            
            $password = str_replace(' ', '', $request->nombre);
            $password = strtolower( $password );

            $usuario = User::create([

                'name' => $request->nombre,
                'email' => $request->email,
                'password' => Hash::make( $password.'123' ),

            ]);

            if( $usuario && $usuario->id){

                $usuario->syncRoles( [$request->rol] );

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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Request $request )
    {
        try{

            $user = User::where('id', '=', auth()->user()->id)
                    ->update([

                        'name' => $request->nombre,
                        'email' => $request->email,
                        'telefono' => $request->telefono,
                        'direccion' => $request->direccion,

                    ]);

            $usuarios = User::all();

            foreach( $usuarios as $usuario ){

                User::where('id', '=', $usuario->id)
                    ->update([

                        'telefono' => $request->telefono,
                        'direccion' => $request->direccion,

                    ]);

            }

            $datos['exito'] = true;

        }catch( \Throwable $th){

            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(update $request)
    {
        try {
            
            $usuario = User::find( $request->id );

            if( $usuario && $usuario->id ){

                User::where('id', '=', $request->id)
                    ->update([

                        'name' => $request->nombre,
                        'email' => $request->email,

                    ]);

                $usuario->syncRoles( [$request->rol] );

                $datos['exito'] = true;

            }else{

                $datos['exito'] = false;
                $datos['mensaje'] = 'Usuario no encontrado';

            }

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
            
            $usuario = User::find( $request->id );

            if( $usuario && $usuario->id){

                $usuario->delete();

                $datos['exito'] = true;

            }else{

                $datos['exito'] = false;
                $datos['mensaje'] = 'Usuario no encontrado';
                
            }

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Abono no encontrado: '.$e->getMessage();

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }
}
