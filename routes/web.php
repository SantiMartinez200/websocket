<?php

use App\Http\Controllers\SolicitudController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/table', [SolicitudController::class, 'index'])->name('tabla');
Route::get('/aceptar/{id}', [SolicitudController::class, 'aceptar'])->name('aceptar');
Route::get('/rechazar/{id}', [SolicitudController::class, 'rechazar'])->name('rechazar');
Route::get('/ver/{id}',[SolicitudController::class,'ver'])->name('ver');
Route::get('/actualizar/{id}',[SolicitudController::class,'actualizar'])->name('actualizar');


Route::get('/solicitud/{dni}',[SolicitudController::class,'solicitud'])->name('solicitud');