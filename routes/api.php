<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CorsMiddleware;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('/guardar-datos', [DatosController::class, 'guardarDatos']);

use App\Http\Controllers\Api\PesoController;
use App\Http\Controllers\Api\DatosController;
Route::post('/guardar-datos', [DatosController::class, 'guardarDatos'])->middleware(CorsMiddleware::class);

//Indaves
Route::post('/peso', [PesoController::class, 'guardarPeso'])->middleware(CorsMiddleware::class);
Route::get('/peso', [PesoController::class, 'obtenerPeso'])->middleware(CorsMiddleware::class);

