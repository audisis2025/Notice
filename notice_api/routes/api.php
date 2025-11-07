<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// --- 1. IMPORTAMOS EL CONTROLADOR QUE ACABAMOS DE CREAR ---
use App\Http\Controllers\Api\BusinessController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ESTA ES NUESTRA NUEVA RUTA:
// Cuando se haga un 'GET' a '/businesses', llamará a la función 'index'
Route::get('/businesses', [BusinessController::class, 'index']);


// Esta ruta ya venía por defecto, la dejamos por si acaso
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});