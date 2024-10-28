<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Http\Request;
use App\Http\Requests\Cliente\Create;
use App\Http\Requests\Cliente\Read;
use App\Http\Requests\Cliente\Update;
use App\Http\Requests\Cliente\Delete;
use App\Models\Producto;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            
            $clientes = Cliente::orderBy('created_at', 'desc')->get();

            return view('clientes.index', compact('clientes'));

        } catch (\Throwable $th) {
            
            echo 'Error al obtener los clientes: '.$th->getMessage();

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( $id )
    {
        try {
            
            $cliente = Cliente::find( $id );
            $productos = Producto::orderBy('nombre', 'asc')->get();

            return view('clientes.productos', compact('cliente', 'productos'));

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            echo "Cliente\Productos no encontrado(s): ".$e->getMessage();

        } catch (\Throwable $th) {
            
            echo 'Error al obtener los productos del cliente: '.$th->getMessage();
            
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Create $request)
    {
        try {
            
            $cliente = Cliente::create([

                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'domicilio' => $request->domicilio,
                'deuda' => 0,

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
    public function show( $id )
    {
        try {
            
            $pedidos = Pedido::where('idCliente', '=', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();

            $cliente = Cliente::find( $id );

            if( $cliente->id ){

                return view('clientes.pedidos', compact('pedidos', 'cliente'));

            }

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            echo "Cliente\Pedidos no encontrado(s): ".$e->getMessage();

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        try {
            
            $pedido = Pedido::find( $request->pedido );

            if( $pedido && $pedido->id ){

                $cliente = Cliente::find( $pedido->idCliente );

                if( $cliente && $cliente->id ){

                    $deuda = is_numeric( $cliente->deuda) ? floatval($cliente->deuda) : round( floatval( $cliente->deuda), 2);
                    $total = is_numeric( $pedido->total) ? floatval( $pedido->total ) : round( floatval( $pedido->total), 2);
                    $totalDeuda = $deuda + $total;

                    $cliente = Cliente::where('id', '=', $pedido->idCliente)
                            ->update([

                                'deuda' => $totalDeuda,
                            
                            ]);

                }

            }

            return true;

        } catch( \Illuminate\Validation\ValidationException $e ){

            echo 'Error de validación: '.$e->getMessage();

            return false;

        } catch( \Illuminate\Database\QueryException $e){

            echo 'Error en la base de datos: '.$e->getMessage();

            return false;

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

            return false;

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request)
    {
        try {
            
            $cliente = Cliente::where('id', '=', $request->id)
                        ->update([

                            'nombre' => $request->nombre,
                            'telefono' => $request->telefono,
                            'domicilio' => $request->domicilio,

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
            
            $cliente = Cliente::find( $request->id );

            if( $cliente->id ){

                $cliente->delete();

                $datos['exito'] = true;

            }else{

                $datos['exito'] = false;
                $datos['mensaje'] = 'Cliente no encontrado';

            }

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Cliente no encontrado: '.$e->getMessage();

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }
}
