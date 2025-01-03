<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/profile/username', [App\Http\Controllers\UserController::class, 'create'])->name('perfil-usuario');

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
Route::post('/pedidos/ventas', [App\Http\Controllers\PedidoController::class, 'ventas'])->name('ventas');
Route::post('/pedido/imprimir', [App\Http\Controllers\PedidoController::class, 'imprimir'])->name('imprimir-pedido');
Route::post('/pedido/borrar', [App\Http\Controllers\PedidoController::class, 'destroy'])->name('borrar-pedido');

Route::get('/cortes', [App\Http\Controllers\CorteController::class, 'index'])->name('cortes');
Route::post('/corte/nuevo', [App\Http\Controllers\CorteController::class, 'create'])->name('nuevo-corte');
Route::post('/corte/agregar',[App\Http\Controllers\CorteController::class, 'store'])->name('agregar-corte');
Route::post('/corte/buscar', [App\Http\Controllers\CorteController::class, 'show'])->name('buscar-corte');

Route::get('/cliente/abonos/{id}', [App\Http\Controllers\AbonoController::class, 'index'])->name('abonos-cliente');
Route::post('/abono/agregar', [App\Http\Controllers\AbonoController::class, 'store'])->name('agregar-abono');
Route::post('/abono/actualizar', [App\Http\Controllers\AbonoController::class, 'update'])->name('actualizar-abono');
Route::post('/abono/borrar', [App\Http\Controllers\AbonoController::class, 'destroy'])->name('borrar-abono');
Route::post('/abono/imprimir', [App\Http\Controllers\AbonoController::class, 'imprimir'])->name('imprimir-abono');
Route::post('/abono/pedidos', [App\Http\Controllers\AbonoController::class, 'show'])->name('pedidos-abono');

Route::get('/cliente/prestamos/{id}', [App\Http\Controllers\PrestamoController::class, 'index'])->name('prestamos-cliente');
Route::post('/prestamo/agregar', [App\Http\Controllers\PrestamoController::class, 'store'])->name('agregar-prestamo');
Route::post('/prestamo/actualizar', [App\Http\Controllers\PrestamoController::class, 'update'])->name('actualizar-prestamo');
Route::post('/prestamo/borrar', [App\Http\Controllers\PrestamoController::class, 'destroy'])->name('borrar-prestamo');
Route::post('/prestamo/imprimir', [App\Http\Controllers\PrestamoController::class, 'imprimir'])->name('imprimir-prestamo');

Route::get('/cajas', [App\Http\Controllers\CajaController::class, 'index'])->name('cajas');
Route::post('/caja/agregar', [App\Http\Controllers\CajaController::class, 'store'])->name('agregar-caja');
Route::post('/caja/actualizar', [App\Http\Controllers\CajaController::class, 'update'])->name('actualizar-caja');
Route::post('/caja/borrar', [App\Http\Controllers\CajaController::class, 'destroy'])->name('borrar-caja');
Route::post('/caja/importe', [App\Http\Controllers\CajaController::class, 'edit'])->name('importe-caja');

Route::get('/gastos/{id}', [App\Http\Controllers\GastoController::class, 'index'])->name('gastos');
Route::post('/gasto/agregar', [App\Http\Controllers\GastoController::class, 'store'])->name('agregar-gasto');
Route::post('/gasto/actualizar', [App\Http\Controllers\GastoController::class, 'update'])->name('actualizar-gasto');
Route::post('/gasto/borrar', [App\Http\Controllers\GastoController::class, 'destroy'])->name('borrar-gasto');

Route::get('/usuarios', [App\Http\Controllers\UserController::class, 'index'])->name('usuarios');
Route::post('/usuario/agregar', [App\Http\Controllers\UserController::class, 'store'])->name('agregar-usuario');
Route::post('/usuario/actualizar', [App\Http\Controllers\UserController::class, 'update'])->name('actualizar-usuario');
Route::post('/usuario/borrar', [App\Http\Controllers\UserController::class, 'destroy'])->name('borrar-usuario');
Route::post('/usuario/perfil', [App\Http\Controllers\UserController::class, 'edit'])->name('actualizar-perfil');

Route::get('/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('roles');
Route::post('/rol/agregar', [App\Http\Controllers\RoleController::class, 'store'])->name('agregar-rol');
Route::post('/rol/actualizar', [App\Http\Controllers\RoleController::class, 'update'])->name('actualizar-rol');
Route::post('/rol/borrar', [App\Http\Controllers\RoleController::class, 'destroy'])->name('borrar-rol');
Route::post('/rol/permisos', [App\Http\Controllers\RoleController::class, 'create'])->name('permisos-rol');
Route::post('/rol/permissions', [App\Http\Controllers\RoleController::class, 'show'])->name('permissions-rol');

Route::get('/permisos', [App\Http\Controllers\PermisoController::class, 'index'])->name('permisos');
Route::post('/permiso/agregar', [App\Http\Controllers\PermisoController::class, 'store'])->name('agregar-permiso');
Route::post('/permiso/actualizar', [App\Http\Controllers\PermisoController::class, 'update'])->name('actualizar-permiso');
Route::post('/permiso/borrar', [App\Http\Controllers\PermisoController::class, 'destroy'])->name('borrar-permiso');