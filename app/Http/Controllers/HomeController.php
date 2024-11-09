<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Pedido;
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

        if( count( $registros ) > 0 ){

            foreach( $registros as $registro ){

                $registro->delete();

            }

        }

        $productos = Producto::all();
        $clientes = Cliente::all();
        $pedidos = Pedido::all();

        return view('index', compact('productos', 'pedidos', 'clientes'));
    }
}
