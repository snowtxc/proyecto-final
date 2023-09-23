<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::group([],function () {
    Route::get('/listar', [UsuarioController::class, "getUsers"]);
});

    





