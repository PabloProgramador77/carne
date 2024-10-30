<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Role\Create;
use App\Http\Requests\Role\Update;
use App\Http\Requests\Role\Delete;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            
            $roles = Role::all();
            $permisos = Permission::all();

            return view('usuarios.roles.index', compact('roles', 'permisos'));

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( Request $request )
    {
        try {
            
            $role = Role::find( $request->id );

            if( $role && $role->id ){

                $role->syncPermissions( $request->permisos );

                $datos['exito'] = true;
                
            }else{

                $datos['exito'] = false;
                $datos['mensaje'] = 'Rol no identificado';

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
    public function store(Create $request)
    {
        try {
            
            $role = Role::create([

                'name' => $request->nombre,

            ]);

            $datos['exito']= true;

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
            
            $rol = Role::find( $request->id );

            if( $rol && $rol->id ){

                $permisos = $rol->permissions;

                $datos['exito'] = true;
                $datos['permisos'] = $permisos;

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request)
    {
        try {
            
            $role = Role::where('id', '=', $request->id)
                    ->update([

                        'name' => $request->nombre,

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
            
            $role = Role::find( $request->id );

            if( $role && $role->id ){

                $role->delete();

                $datos['exito'] = true;

            }

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }
}
