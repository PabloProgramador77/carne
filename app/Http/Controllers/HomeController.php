<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Cortes;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $registros = Pedido::where('created_at', '<', now()->subDays(30))
                    ->where('estado', '=', 'Corte')
                    ->get();

        $cortes = Cortes::where('created_at', '<', now()->subDays(15))
                    ->get();

        if( count( $registros ) > 0 ){

            foreach( $registros as $registro ){

                $registro->delete();

            }

        }

        if( count( $cortes ) > 0 ){
            
            foreach( $cortes as $corte ){

                $corte->delete();

            }
            
        }

        $productos = Producto::all();
        $clientes = Cliente::all();
        $pedidos = Pedido::all();

        return view('index', compact('productos', 'pedidos', 'clientes'));
    }
}
