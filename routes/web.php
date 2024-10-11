<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/productos', [App\Http\Controllers\ProductoController::class, 'index'])->name('productos');
Route::post('/producto/agregar', [App\Http\Controllers\ProductoController::class, 'store'])->name('agregar-producto');
Route::post('/producto/actualizar', [App\Http\Controllers\ProductoController::class, 'update'])->name('actualizar-producto');
Route::post('/producto/borrar', [App\Http\Controllers\ProductoController::class, 'destroy'])->name('borrar-producto');

Route::get('/clientes', [App\Http\Controllers\ClienteController::class, 'index'])->name('clientes');
Route::post('/cliente/agregar', [App\Http\Controllers\ClienteController::class, 'store'])->name('agregar-cliente');
Route::post('/cliente/actualizar', [App\Http\Controllers\ClienteController::class, 'update'])->name('actualizar-cliente');
Route::post('/cliente/borrar', [App\Http\Controllers\ClienteController::class, 'destroy'])->name('borrar-cliente');
Route::get('/cliente/productos/{id}', [App\Http\Controllers\ClienteController::class, 'create'])->name('productos-de-cliente');
Route::post('/cliente/precios', [App\Http\Controllers\ClienteHasProductoController::class, 'store'])->name('precios-de-cliente');
Route::get('/cliente/pedidos/{id}', [App\Http\Controllers\ClienteController::class, 'show'])->name('pedidos-de-cliente');

Route::get('/pedidos', [App\Http\Controllers\PedidoController::class, 'index'])->name('pedidos');
Route::get('/pedido/cliente/{id}', [App\Http\Controllers\PedidoController::class, 'store'])->name('nuevo-pedido');
Route::post('/pedido/pesos', [App\Http\Controllers\PedidoHasProductoController::class, 'store'])->name('pesos-de-pedido');
Route::post('/pedido/cobrar', [App\Http\Controllers\PedidoHasProductoController::class, 'create'])->name('cobrar-pedido');
Route::post('/pedido/cancelar', [App\Http\Controllers\PedidoController::class, 'destroy'])->name('cancelar-pedido');
Route::post('/pedido/buscar', [App\Http\Controllers\PedidoController::class, 'show'])->name('buscar-pedido');
Route::post('/pedido/pagar', [App\Http\Controllers\PedidoController::class, 'update'])->name('pagar-pedido');

Route::get('/cortes', [App\Http\Controllers\CorteController::class, 'index'])->name('cortes');
Route::post('/corte/nuevo', [App\Http\Controllers\CorteController::class, 'create'])->name('nuevo-corte');
Route::post('/corte/agregar',[App\Http\Controllers\CorteController::class, 'store'])->name('agregar-corte');
Route::post('/corte/buscar', [App\Http\Controllers\CorteController::class, 'show'])->name('buscar-corte');

Route::get('/cliente/abonos/{id}', [App\Http\Controllers\AbonoController::class, 'index'])->name('abonos-cliente');
Route::post('/abono/agregar', [App\Http\Controllers\AbonoController::class, 'store'])->name('agregar-abono');
Route::post('/abono/actualizar', [App\Http\Controllers\AbonoController::class, 'update'])->name('actualizar-abono');
Route::post('/abono/borrar', [App\Http\Controllers\AbonoController::class, 'destroy'])->name('borrar-abono');

Route::get('/cliente/prestamos/{id}', [App\Http\Controllers\PrestamoController::class, 'index'])->name('prestamos-cliente');
Route::post('/prestamo/agregar', [App\Http\Controllers\PrestamoController::class, 'store'])->name('agregar-prestamo');
Route::post('/prestamo/actualizar', [App\Http\Controllers\PrestamoController::class, 'update'])->name('actualizar-prestamo');
Route::post('/prestamo/borrar', [App\Http\Controllers\PrestamoController::class, 'destroy'])->name('borrar-prestamo');