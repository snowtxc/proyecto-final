<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\ProcesoController;
use App\Http\Controllers\EtapaController;
use App\Http\Controllers\ComponenteController;
use App\Http\Controllers\ComponenteImagenController;
use App\Http\Controllers\ParteController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('api')->group(function () {

    /*GRUPOS ENDPOINTS*/
    Route::controller(GrupoController::class)->group(function () {
        Route::get('/grupos', 'list');
        Route::post('/grupos', 'create');
        Route::put('/grupos/{id}', 'update');
        Route::delete('/grupos/{id}', 'delete');
    });

    /*PROCESO ENDPOINTS*/
    Route::controller(ProcesoController::class)->group(function () {
        Route::get('/procesos', 'list');
        Route::post('/procesos', 'create');
        Route::put('/procesos/{id}', 'update');
        Route::delete('/procesos/{id}', 'delete');
    });

     /*ETAPA  ENDPOINTS*/
     Route::controller(EtapaController::class)->group(function () {
        Route::get('/etapas', 'list');
        Route::post('/etapas', 'create');
        Route::put('/etapas/{id}', 'update');
        Route::delete('/etapas/{id}', 'delete');
    });

     /*COMPONENTE  ENDPOINTS*/
     Route::controller(ComponenteController::class)->group(function () {
        Route::get('/componentes', 'list');
        Route::post('/componentes', 'create');
        Route::put('/componentes/{id}', 'update');
        Route::delete('/componentes/{id}', 'delete');
        Route::get('/componentes/{componenteId}/imagenes/{imageId}', 'getImage');
        Route::post('/componentes/{id}/imagenes', 'addImageById');
        Route::delete('/componentes/{componenteId}/imagenes/{imageId}', 'removeImageById');

    });

    /*PARTE ENDPOINTS*/
    Route::controller(ParteController::class)->group(function () {
        Route::get('/partes', 'list');
        Route::post('/partes', 'create');
        Route::put('/partes/{id}', 'update');
        Route::delete('/partes/{id}', 'delete');
    });




});
