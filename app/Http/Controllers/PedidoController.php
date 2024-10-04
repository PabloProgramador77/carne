<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\ClienteHasProducto;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

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
    public function create( $id )
    {
        try {
            
            $ticket = new \Mpdf\Mpdf([

                'mode' => 'utf-8',
                'format' => ['80', '2750'],
                'orientation' => 'P',
                'autoPageBreak' => false,

            ]);

            $pedido = Pedido::find( $id );

            if( $pedido->id ){

                $productos = ClienteHasProducto::select('productos.nombre', 'cliente_has_productos.precio', 'pedido_has_productos.cantidad')
                            ->join('productos', 'cliente_has_productos.idProducto', '=', 'productos.id')
                            ->join('pedido_has_productos', 'cliente_has_productos.id', '=', 'pedido_has_productos.idClienteHasProducto')
                            ->where('pedido_has_productos.idPedido', '=', $id)
                            ->orderBy('productos.nombre', 'asc')
                            ->get();

                if( count( $productos ) > 0 ){

                    $total = 0;

                    $ticket->writeHTML('<h4 style="text-align: center;">BLVD AQUILES SERDAN #1001</h4>');
                    $ticket->writeHTML('<h5 style="text-align: center;">476 587 6390</h5>');
                    $ticket->writeHTML('<h6 style="text-align: center;">'.$pedido->created_at.'</h6>');
                    $ticket->writeHTML('<h6 style="text-align: center;">Cliente: '.$pedido->cliente->nombre.'</h6>');
                    $ticket->writeHTML('<h6 style="text-align: center;">Folio: '.$pedido->id.'</h6>');

                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto;">');
                    $ticket->writeHTML('<thead style="border-bottom: 2px;">');
                    $ticket->writeHTML('<tr><th>Cantidad</th><th>Producto</th><th>Precio</th><th>Importe</th></tr>');
                    $ticket->writeHTML('</thead>');
                    $ticket->writeHTML('<tbody>');

                    foreach( $productos as $producto ){

                        $ticket->writeHTML('<tr>');
                        $ticket->writeHTML('<td>'.$producto->cantidad.'</td>');
                        $ticket->writeHTML('<td>'.$producto->nombre.'</td>');
                        $ticket->writeHTML('<td>$'.$producto->precio.'</td>');
                        $ticket->writeHTML('<td>$'.number_format( ($producto->cantidad * $producto->precio), 2 ).'</td>');
                        $ticket->writeHTML('</tr>');

                        $total += number_format( ($producto->cantidad * $producto->precio), 2 );

                    }

                    $ticket->writeHTML('</tbody>');
                    $ticket->writeHTML('</table>');
                    $ticket->writeHTML('<p style="text-align: center; ">Total: $ '.number_format( $total, 2).' MXN</p>');

                }

            }

            if( !file_exists( public_path('tickets') ) ){

                mkdir( public_path('tickets') );

            }

            $ticket->Output( public_path('tickets/').'ticket'.$pedido->id.'.pdf', \Mpdf\Output\Destination::FILE );

            if( file_exists( public_path('tickets/').'ticket'.$pedido->id.'.pdf' ) ){

                return true;

            }else{

                return false;

            }

        } catch (\Throwable $th) {
             
            echo $th->getMessage();

        }
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

            $totalPedido = is_numeric( $total ) ? number_format( $total, 2 ) : 0;

            $pedido = Pedido::where('id', '=', $request->pedido)
                    ->update([

                        'total' => $totalPedido,
                        'nota' => $request->nota,

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
