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

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            echo "Pedido(s) no encontrado(s): ".$e->getMessage();

        } catch (\Throwable $th) {
            
            echo $th->getMessage();

        }
    }

    /*     * Show the form for creating a new resource.
     */
    public function create( $id )
    {
        try {
            
            $ticket = new \Mpdf\Mpdf([

                'mode' => 'utf-8',
                'format' => ['58', '2750'],
                'orientation' => 'P',
                'autoPageBreak' => false,
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 5,
                'margin_bottom' => 5,

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

                    $ticket->writeHTML('<h4 style="text-align: center;">Carniceria La Higienica</h4>');
                    $ticket->writeHTML('<p style="text-align: center; display: block; width: 100%;">'.( auth()->user()->telefono ? : '' ).'</p>');
                    $ticket->writeHTML('<p style="text-align: center; display: block; width: 100%;">'.( auth()->user()->direccion ? : '' ).'</p>');
                    $ticket->writeHTML('<h6 style="text-align: center;"><b>Fecha:</b>'.$pedido->created_at->format('dd/mm/yy g:i A').'</h6>');
                    $ticket->writeHTML('<h5 style="text-align: center;"><b>Cliente:</b>'.$pedido->cliente->nombre.'</h5>');
                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
                    $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Cajero:</b></td><td>'.auth()->user()->name.'</td></tr>');
                    $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Folio:</b></td><td>'.$pedido->id.'</td></tr>');
                    $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Concepto:</b></td><td>Compra</td></tr>');
                    $ticket->writeHTML('</table>');

                    if( $pedido->nota !== '' || $pedido->nota !== NULL || $pedido->nota !== 'Sin nota' ){

                        $ticket->writeHTML('<p style="font-size: 12px; overflow: auto;"><b>Nota:</b> '.$pedido->nota.'</p>');

                    }

                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto;">');
                    $ticket->writeHTML('<thead style="border-bottom: 2px;">');
                    $ticket->writeHTML('<tr><th>Cant.</th><th>Prod.</th><th>Precio</th><th>Importe</th></tr>');
                    $ticket->writeHTML('</thead>');
                    $ticket->writeHTML('<tbody>');

                    foreach( $productos as $producto ){

                        $cantidad = is_numeric( $producto->cantidad ) ? floatval( $producto->cantidad ) : round( floatval( $producto->cantidad ) );
                        $precio = is_numeric( $producto->precio ) ? floatval( $producto->precio ) : round( floatval( $producto->precio ) );

                        $ticket->writeHTML('<tr>');
                        $ticket->writeHTML('<td style="font-size: 18px;">'.number_format($producto->cantidad, 1).'</td>');
                        $ticket->writeHTML('<td style="font-size: 18px;">'.$producto->nombre.'</td>');
                        $ticket->writeHTML('<td style="font-size: 18px;">$'.number_format($producto->precio, 1).'</td>');
                        $ticket->writeHTML('<td style="font-size: 18px;">$'.number_format( ($cantidad * $precio), 1 ).'</td>');
                        $ticket->writeHTML('</tr>');

                        $total += ($cantidad * $precio);

                    }

                    $ticket->writeHTML('</tbody>');
                    $ticket->writeHTML('</table>');
                    $ticket->writeHTML('<p style="text-align: center;"><b>Total: $ '.number_format( $total, 2).'</b></p>');
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

        } catch( \Mpdf\MpdfException $e ){

            echo 'Error al general el ticket del pedido: '.$e->getMessage();

            return false;

        } catch (\Throwable $th) {
             
            echo $th->getMessage();

            return false;

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

        } catch( \Illuminate\Validation\ValidationException $e ){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Error de validación: '.$e->getMessage();

        } catch( \Illuminate\Database\QueryException $e){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Error en la base de datos: '.$e->getMessage();

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

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            echo "Pedido\Producto(s) no encontrado(s): ".$e->getMessage();

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

            $nota = $request->nota ? $request->nota : 'Sin nota';
            $totalPedido = str_replace(',', '', $total);
            $totalPedido = is_numeric( $totalPedido ) ? floatval( $totalPedido ) :  round( floatval( $totalPedido ));

            Pedido::where('id', '=', $request->pedido)
                    ->update([

                        'total' => $totalPedido,
                        'nota' => $nota,

                    ]);

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
    public function update(Request $request )
    {
        try {
            
            Pedido::where('id', '=', $request->pedido)
                    ->update([

                        'estado' => $request->estado,

                    ]);

            $pedido = Pedido::find( $request->pedido );

            $idCliente = $pedido->idCliente;

            $cliente = Cliente::find( $idCliente );

            if( $pedido->estado === 'Pagado'){

                $deuda = is_numeric( $cliente->deuda ) ? floatval( $cliente->deuda ) : round( floatval( $cliente->deuda ) );
                $totalPedido = is_numeric( $pedido->total ) ? floatval( $pedido->total ) : round( floatval( $pedido->total ) );
                
                $total = $deuda - $totalPedido;

                Cliente::where('id', '=', $idCliente)
                    ->update([

                        'deuda' => $total,

                    ]);

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
     * Remove the specified resource from storage.
     */
    public function destroy( Request $request )
    {
        try {

            $pedido = Pedido::find( $request->id );

            if( $pedido->id ){

                $pedido->delete();

                $datos['exito'] = true;

            }else{

                $datos['exito'] = false;
                $datos['mensaje'] = 'Pedido no encontrado';

            }

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Pedido no encontrado: '.$e->getMessage();

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

        } catch( \Illuminate\Database\Eloquent\ModelNotFoundException $e){

            $datos['exito'] = false;
            $datos['mensaje'] = 'Abono no encontrado: '.$e->getMessage();

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
                'format' => ['58', '2750'],
                'orientation' => 'P',
                'autoPageBreak' => false,
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 5,
                'margin_bottom' => 5,

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

                    $ticket->writeHTML('<h4 style="text-align: center;">Carniceria La Higienica</h4>');
                    $ticket->writeHTML('<p style="text-align: center; display: block; width: 100%;">'.( auth()->user()->telefono ? : '' ).'</p>');
                    $ticket->writeHTML('<p style="text-align: center; display: block; width: 100%;">'.( auth()->user()->direccion ? : '' ).'</p>');
                    $ticket->writeHTML('<h6 style="text-align: center;"><b>Fecha:</b>'.$pedido->created_at->format('dd/mm/yy g:i A').'</h6>');
                    $ticket->writeHTML('<h5 style="text-align: center;"><b>Cliente:</b>'.$pedido->cliente->nombre.'</h5>');
                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
                    $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Cajero:</b></td><td>'.auth()->user()->name.'</td></tr>');
                    $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Folio:</b></td><td>'.$pedido->id.'</td></tr>');
                    $ticket->writeHTML('<tr><td style="font-size: 16px;"><b>Concepto:</b></td><td>Compra</td></tr>');
                    $ticket->writeHTML('</table>');

                    if( $pedido->nota !== '' || $pedido->nota !== NULL || $pedido->nota !== 'Sin nota' ){

                        $ticket->writeHTML('<p style="font-size: 11px; display: block; overflow: auto;"><b>Nota:</b> '.$pedido->nota.'</p>');
                        
                    }

                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto;">');
                    $ticket->writeHTML('<thead style="border-bottom: 2px;">');
                    $ticket->writeHTML('<tr><th>Cant.</th><th>Prod.</th><th>Precio</th><th>Importe</th></tr>');
                    $ticket->writeHTML('</thead>');
                    $ticket->writeHTML('<tbody>');

                    foreach( $productos as $producto ){

                        $cantidad = is_numeric( $producto->cantidad ) ? floatval( $producto->cantidad ) : round( floatval( $producto->cantidad ) );
                        $precio = is_numeric( $producto->precio ) ? floatval( $producto->precio ) : round( floatval( $producto->precio ) );

                        $ticket->writeHTML('<tr>');
                        $ticket->writeHTML('<td style="font-size: 18px;">'.number_format($producto->cantidad, 1).'</td>');
                        $ticket->writeHTML('<td style="font-size: 18px;">'.$producto->nombre.'</td>');
                        $ticket->writeHTML('<td style="font-size: 18px;">$'.number_format($producto->precio, 1).'</td>');
                        $ticket->writeHTML('<td style="font-size: 18px;">$'.number_format( ($cantidad * $precio), 1 ).'</td>');
                        $ticket->writeHTML('</tr>');

                        $total += ($cantidad * $precio);

                    }

                    $ticket->writeHTML('</tbody>');
                    $ticket->writeHTML('</table>');
                    $ticket->writeHTML('<p style="text-align: center; "><b>Total: $ '.number_format( $total, 2).'</b></p>');
                    $ticket->writeHTML('<p style="text-align: center; margin-top: 10px;">**TICKET COPIA**</p>');

                }

            }

            if( !file_exists( public_path('tickets') ) ){

                mkdir( public_path('tickets') );

            }

            $ticket->Output( public_path('tickets/').'copia'.$pedido->id.'.pdf', \Mpdf\Output\Destination::FILE );

            if( file_exists( public_path('tickets/').'copia'.$pedido->id.'.pdf' ) ){

                shell_exec('PDFtoPrinter.exe '.public_path('tickets/').'copia'.$pedido->id.'.pdf "POS-58"');
                shell_exec('PDFtoPrinter.exe '.public_path('tickets/').'ticket'.$pedido->id.'.pdf "POS-58"');

                return true;

            }else{

                return false;

            }

        } catch( \Mpdf\MpdfException $e){

            echo "Error al generar la copia del pedido: ".$e->getMessage();

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
                'format' => ['58', '2750'],
                'orientation' => 'P',
                'autoPageBreak' => false,
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 5,
                'margin_bottom' => 5,

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

                    $ticket->writeHTML('<h4 style="text-align: center;">Carniceria La Higienica</h4>');
                    $ticket->writeHTML('<p style="text-align: center; display: block; width: 100%;">'.( auth()->user()->telefono ? : '' ).'</p>');
                    $ticket->writeHTML('<p style="text-align: center; display: block; width: 100%;">'.( auth()->user()->direccion ? : '' ).'</p>');
                    $ticket->writeHTML('<h6 style="text-align: center;"><b>Fecha:</b>'.$pedido->created_at->format('dd/mm/yy g:i A').'</h6>');
                    $ticket->writeHTML('<h5 style="text-align: center;"><b>Cliente:</b>'.$pedido->cliente->nombre.'</h5>');
                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto; margin-bottom: 10px;">');
                    $ticket->writeHTML('<tr><td>Cajero:</td><td>'.auth()->user()->name.'</td></tr>');
                    $ticket->writeHTML('<tr><td>Folio:</td><td>'.$pedido->id.'</td></tr>');
                    $ticket->writeHTML('<tr><td>Concepto:</td><td>Compra</td></tr>');
                    $ticket->writeHTML('</table>');

                    if( $pedido->nota !== '' || $pedido->nota !== NULL || $pedido->nota !== 'Sin nota' ){

                        $ticket->writeHTML('<p style="font-size: 11px; display: block; overflow: auto;"><b>Nota:</b> '.$pedido->nota.'</p>');
                        
                    }

                    $ticket->writeHTML('<table style="width: 100%; height: auto; overflow: auto;">');
                    $ticket->writeHTML('<thead style="border-bottom: 2px;">');
                    $ticket->writeHTML('<tr><th>Cant.</th><th>Prod.</th><th>Precio</th><th>Importe</th></tr>');
                    $ticket->writeHTML('</thead>');
                    $ticket->writeHTML('<tbody>');

                    foreach( $productos as $producto ){

                        $cantidad = is_numeric( $producto->cantidad ) ? floatval( $producto->cantidad ) : round( floatval( $producto->cantidad ) );
                        $precio = is_numeric( $producto->precio ) ? floatval( $producto->precio ) : round( floatval( $producto->precio ) );

                        $ticket->writeHTML('<tr>');
                        $ticket->writeHTML('<td style="font-size: 18px;">'.number_format($producto->cantidad, 1).'</td>');
                        $ticket->writeHTML('<td style="font-size: 18px;">'.$producto->nombre.'</td>');
                        $ticket->writeHTML('<td style="font-size: 18px;">$'.number_format($producto->precio, 1).'</td>');
                        $ticket->writeHTML('<td style="font-size: 18px;">$'.number_format( ($cantidad * $precio), 1 ).'</td>');
                        $ticket->writeHTML('</tr>');

                        $total += ($cantidad * $precio);

                    }

                    $ticket->writeHTML('</tbody>');
                    $ticket->writeHTML('</table>');
                    $ticket->writeHTML('<p style="text-align: center; "><b>Total: $ '.number_format( $total, 2).'</b></p>');
                    $ticket->writeHTML('<p style="text-align: center; margin-top: 10px;">**TICKET REIMPRESO**</p>');

                }

            }

            if( !file_exists( public_path('tickets') ) ){

                mkdir( public_path('tickets') );

            }

            $ticket->Output( public_path('tickets/').'reimpresion'.$pedido->id.'.pdf', \Mpdf\Output\Destination::FILE );

            if( file_exists( public_path('tickets/').'reimpresion'.$pedido->id.'.pdf' ) ){

                shell_exec('PDFtoPrinter.exe '.public_path('tickets/').'reimpresion'.$pedido->id.'.pdf "POS-58"');

                $datos['exito'] = true;

            }

        } catch( \Mpdf\MpdfException $e){

            $datos['exito'] = false;
            $datos['mensaje'] = "Error al reimprimir el pedido: ".$e->getMessage();

        } catch (\Throwable $th) {
            
            $datos['exito'] = false;
            $datos['mensaje'] = $th->getMessage();
            
        }
        
        return response()->json( $datos );
    }

}
