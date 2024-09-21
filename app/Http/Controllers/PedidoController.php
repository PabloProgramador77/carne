<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\ClienteHasProducto;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            
            $pedidos = Pedido::orderBy('created_at', 'desc')->with('cliente')->get();
            $clientes = Cliente::orderBy('nombre', 'asc')->get();

            return view('pedidos.index', compact('pedidos', 'clientes'));

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
    public function store( $id )
    {
        try {
            
            $cliente = Cliente::find( $id );

            $pedido = Pedido::create([
                
                'total' => 0,
                'idCliente' => $cliente->id,

            ]);

            if( $pedido->id && $cliente->id ){
    
                $productos = ClienteHasProducto::select('cliente_has_productos.id', 'productos.nombre', 'cliente_has_productos.precio')
                            ->join('productos', 'cliente_has_productos.idProducto', '=', 'productos.id')
                            ->where('cliente_has_productos.idCliente', '=', $id)
                            ->orderBy('productos.nombre', 'asc')
                            ->get();

                return view('pedidos.create', compact('pedido', 'productos', 'cliente'));

            }

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }
    }

    /**
     * Display the specified resource.
     */
    public function show( Request $request )
    {
        try {
            
            $pedido = Pedido::find( $request->id );

            if( $pedido->id ){

                $datos['exito'] = true;
                $datos['productos'] = ClienteHasProducto::select('productos.nombre', 'cliente_has_productos.precio', 'pedido_has_productos.cantidad')
                                    ->join('productos', 'cliente_has_productos.idProducto', '=', 'productos.id')
                                    ->join('pedido_has_productos', 'cliente_has_productos.id', '=', 'pedido_has_productos.idClienteHasProducto')
                                    ->where('pedido_has_productos.idPedido', '=', $request->id)
                                    ->orderBy('productos.nombre', 'asc')
                                    ->get(); 

            }

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje']= $th->getMessage();

        }

        return response()->json( $datos );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $total)
    {
        try {
            
            $pedido = Pedido::where('id', '=', $request->pedido)
                    ->update([

                        'total' => $total,

                    ]);

            return true;


        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Request $request )
    {
        try {

            $pedido = Pedido::find( $request->id );

            if( $pedido->id ){

                $pedido->delete();

                $datos['exito'] = true;

            }

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }
}
