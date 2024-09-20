<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;

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