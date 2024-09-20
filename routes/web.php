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