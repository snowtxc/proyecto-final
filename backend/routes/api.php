<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\UsuarioController;

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




Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [UsuarioController::class, "login"]);
    Route::post('/logout', [UsuarioController::class, 'logout']);
    Route::post('/refresh', [UsuarioController::class, 'refresh']);
    Route::post('/me', [UsuarioController::class, 'me']);
});

Route::group(["prefix" => 'usuarios'],function () {
    Route::get('/', [UsuarioController::class, "getUsers"]);
    Route::get('/', [UsuarioController::class, 'index']);
    Route::post('/', [UsuarioController::class, 'nuevo']);
    Route::get('/id}', [UsuarioController::class, 'buscar']);
    Route::put('/{id}', [UsuarioController::class, 'editar']);
    Route::delete('/{id}', [UsuarioController::class, 'eliminar']);
});



