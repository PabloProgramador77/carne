<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\ClienteHasProducto;
use App\Models\Caja;
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
            
            $pedidos = Pedido::orderBy('created_at', 'desc')
                    ->where('estado', '!=', 'Corte')
                    ->with('cliente')
                    ->get();

            $clientes = Cliente::orderBy('nombre', 'asc')->get();

            $cajas = Caja::all();

            return view('pedidos.index', compact('pedidos', 'clientes', 'cajas'));

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

                    $ticket->writeHTML('<h4 style="text-align: center;">La Higienica Premium</h4>');
                    $ticket->writeHTML('<h5 style="text-align: center;">476 587 6390</h5>');
                    $ticket->writeHTML('<h6 style="text-align: center;">'.$pedido->created_at.'</h6>');
                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
                    $ticket->writeHTML('<tr><td>Cajero:</td><td>'.auth()->user()->name.'</td></tr>');
                    $ticket->writeHTML('<tr><td>Folio:</td><td>'.$pedido->id.'</td></tr>');
                    $ticket->writeHTML('<tr><td>Cliente:</td><td>'.$pedido->cliente->nombre.'</td></tr>');
                    $ticket->writeHTML('<tr><td>Concepto:</td><td>Compra</td></tr>');
                    $ticket->writeHTML('</table>');

                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto;">');
                    $ticket->writeHTML('<thead style="border-bottom: 2px;">');
                    $ticket->writeHTML('<tr><th>Cantidad</th><th>Producto</th><th>Importe</th></tr>');
                    $ticket->writeHTML('</thead>');
                    $ticket->writeHTML('<tbody>');

                    foreach( $productos as $producto ){

                        $ticket->writeHTML('<tr>');
                        $ticket->writeHTML('<td>'.$producto->cantidad.'</td>');
                        $ticket->writeHTML('<td>'.$producto->nombre.'</td>');
                        $ticket->writeHTML('<td>$'.number_format( ($producto->cantidad * $producto->precio), 2 ).'</td>');
                        $ticket->writeHTML('</tr>');

                        $total += number_format( ($producto->cantidad * $producto->precio), 2 );

                    }

                    $ticket->writeHTML('</tbody>');
                    $ticket->writeHTML('</table>');
                    $ticket->writeHTML('<p style="text-align: center; ">Total: $ '.number_format( $total, 2).' MXN</p>');
                    $ticket->writeHTML('<p style="text-align: center; margin-top: 20px;">_____________________</p>');
                    $ticket->writeHTML('<p style="text-align: center;">Firma de '.$pedido->cliente->nombre.'</p>');

                }

            }

            if( !file_exists( public_path('tickets') ) ){

                mkdir( public_path('tickets') );

            }

            $ticket->Output( public_path('tickets/').'ticket'.$pedido->id.'.pdf', \Mpdf\Output\Destination::FILE );

            if( file_exists( public_path('tickets/').'ticket'.$pedido->id.'.pdf' ) ){

                $this->copia( $id );

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
                'estado' => 'Pendiente',

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
    public function update(Request $request )
    {
        try {
            
            $pedido = Pedido::where('id', '=', $request->pedido)
                    ->update([

                        'estado' => $request->estado,

                    ]);

            $pedido = Pedido::find( $request->pedido );

            $idCliente = $pedido->idCliente;

            if( $request->estado === 'Cobrado' ){

                $this->create( $request->pedido );

            }else{

                $cliente = Cliente::find( $idCliente );
                
                $total = floatval( $cliente->deuda - $pedido->total );

                Cliente::where('id', '=', $idCliente)
                        ->update([

                            'deuda' => $total,

                        ]);

            }

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

    /**
     * Consulta de ventas
     * ! Recopila todos los pedidos que no estan en corte
     */
    public function ventas(){
        try {
            
            $pedidos = Pedido::select('pedidos.total', 'pedidos.created_at', 'clientes.nombre', 'pedidos.estado')
                    ->join('clientes', 'pedidos.idCliente', '=', 'clientes.id')
                    ->where('pedidos.estado', '!=', 'Corte')
                    ->get();

            if( count( $pedidos ) > 0 ){

                $datos['exito'] = true;
                $datos['pedidos'] = $pedidos;

            }else{

                $datos['exito'] = false;
                $datos['mensaje'] = 'Sin ventas registradas';
                
            }

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();

        }

        return response()->json( $datos );
    }

    /**
     * Copia de ticket
     */
    public function copia( $id ){
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

                    $ticket->writeHTML('<h4 style="text-align: center;">La Higienica Premium</h4>');
                    $ticket->writeHTML('<h5 style="text-align: center;">4765876390</h5>');
                    $ticket->writeHTML('<h6 style="text-align: center;">'.$pedido->created_at.'</h6>');
                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
                    $ticket->writeHTML('<tr><td>Cajero:</td><td>'.auth()->user()->name.'</td></tr>');
                    $ticket->writeHTML('<tr><td>Folio:</td><td>'.$pedido->id.'</td></tr>');
                    $ticket->writeHTML('<tr><td>Cliente:</td><td>'.$pedido->cliente->nombre.'</td></tr>');
                    $ticket->writeHTML('<tr><td>Concepto:</td><td>Compra</td></tr>');
                    $ticket->writeHTML('</table>');

                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto;">');
                    $ticket->writeHTML('<thead style="border-bottom: 2px;">');
                    $ticket->writeHTML('<tr><th>Cantidad</th><th>Producto</th><th>Importe</th></tr>');
                    $ticket->writeHTML('</thead>');
                    $ticket->writeHTML('<tbody>');

                    foreach( $productos as $producto ){

                        $ticket->writeHTML('<tr>');
                        $ticket->writeHTML('<td>'.$producto->cantidad.'</td>');
                        $ticket->writeHTML('<td>'.$producto->nombre.'</td>');
                        $ticket->writeHTML('<td>$'.number_format( ($producto->cantidad * $producto->precio), 2 ).'</td>');
                        $ticket->writeHTML('</tr>');

                        $total += number_format( ($producto->cantidad * $producto->precio), 2 );

                    }

                    $ticket->writeHTML('</tbody>');
                    $ticket->writeHTML('</table>');
                    $ticket->writeHTML('<p style="text-align: center; ">Total: $ '.number_format( $total, 2).' MXN</p>');
                    $ticket->writeHTML('<p style="text-align: center; margin-top: 10px;">**TICKET COPIA**</p>');

                }

            }

            if( !file_exists( public_path('tickets') ) ){

                mkdir( public_path('tickets') );

            }

            $ticket->Output( public_path('tickets/').'copia'.$pedido->id.'.pdf', \Mpdf\Output\Destination::FILE );

            if( file_exists( public_path('tickets/').'copia'.$pedido->id.'.pdf' ) ){

                shell_exec('PDFtoPrinter.exe '.public_path('tickets/').'copia'.$pedido->id.'.pdf "Microsoft Print to PDF"');
                shell_exec('PDFtoPrinter.exe '.public_path('tickets/').'ticket'.$pedido->id.'.pdf "Microsoft Print to PDF"');

                return true;

            }else{

                return false;

            }

        } catch (\Throwable $th) {
             
            echo $th->getMessage();

        }        
    }

    /**
     * Reimpresión de ticket
     */
    public function imprimir( Request $request ){
        try {
            
            $ticket = new \Mpdf\Mpdf([

                'mode' => 'utf-8',
                'format' => ['80', '2750'],
                'orientation' => 'P',
                'autoPageBreak' => false,

            ]);

            $pedido = Pedido::find( $request->id );

            if( $pedido->id ){

                $productos = ClienteHasProducto::select('productos.nombre', 'cliente_has_productos.precio', 'pedido_has_productos.cantidad')
                            ->join('productos', 'cliente_has_productos.idProducto', '=', 'productos.id')
                            ->join('pedido_has_productos', 'cliente_has_productos.id', '=', 'pedido_has_productos.idClienteHasProducto')
                            ->where('pedido_has_productos.idPedido', '=', $request->id)
                            ->orderBy('productos.nombre', 'asc')
                            ->get();

                if( count( $productos ) > 0 ){

                    $total = 0;

                    $ticket->writeHTML('<h4 style="text-align: center;">La Higienica Premium</h4>');
                    $ticket->writeHTML('<h5 style="text-align: center;">4765876390</h5>');
                    $ticket->writeHTML('<h6 style="text-align: center;">'.$pedido->created_at.'</h6>');
                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
                    $ticket->writeHTML('<tr><td>Cajero:</td><td>'.auth()->user()->name.'</td></tr>');
                    $ticket->writeHTML('<tr><td>Folio:</td><td>'.$pedido->id.'</td></tr>');
                    $ticket->writeHTML('<tr><td>Cliente:</td><td>'.$pedido->cliente->nombre.'</td></tr>');
                    $ticket->writeHTML('<tr><td>Concepto:</td><td>Compra</td></tr>');
                    $ticket->writeHTML('</table>');

                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto;">');
                    $ticket->writeHTML('<thead style="border-bottom: 2px;">');
                    $ticket->writeHTML('<tr><th>Cantidad</th><th>Producto</th><th>Importe</th></tr>');
                    $ticket->writeHTML('</thead>');
                    $ticket->writeHTML('<tbody>');

                    foreach( $productos as $producto ){

                        $ticket->writeHTML('<tr>');
                        $ticket->writeHTML('<td>'.$producto->cantidad.'</td>');
                        $ticket->writeHTML('<td>'.$producto->nombre.'</td>');
                        $ticket->writeHTML('<td>$'.number_format( ($producto->cantidad * $producto->precio), 2 ).'</td>');
                        $ticket->writeHTML('</tr>');

                        $total += number_format( ($producto->cantidad * $producto->precio), 2 );

                    }

                    $ticket->writeHTML('</tbody>');
                    $ticket->writeHTML('</table>');
                    $ticket->writeHTML('<p style="text-align: center; ">Total: $ '.number_format( $total, 2).' MXN</p>');
                    $ticket->writeHTML('<p style="text-align: center; margin-top: 10px;">**TICKET REIMPRESO**</p>');

                }

            }

            if( !file_exists( public_path('tickets') ) ){

                mkdir( public_path('tickets') );

            }

            $ticket->Output( public_path('tickets/').'reimpresion'.$pedido->id.'.pdf', \Mpdf\Output\Destination::FILE );

            if( file_exists( public_path('tickets/').'reimpresion'.$pedido->id.'.pdf' ) ){

                shell_exec('PDFtoPrinter.exe '.public_path('tickets/').'reimpresion'.$pedido->id.'.pdf "Microsoft Print to PDF"');

                $datos['exito'] = true;

            }

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();
            
        }
        
        return response()->json( $datos );
    }

}
