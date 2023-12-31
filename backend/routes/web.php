<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\ProcesoController;
use App\Http\Controllers\EtapaController;
use App\Http\Controllers\ComponenteController;
use App\Http\Controllers\ComponenteImagenController;
use App\Http\Controllers\ParteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TipoComponenteController;
use App\Http\Controllers\AlarmaController;
use App\Http\Controllers\NodoController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\FtpController;
use App\Http\Controllers\EstadisticaController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\ReporteController;


use App\Events\Hello;





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



Route::prefix('api')->group(function () {

    /*AUTH ENDPOINTS*/
    Route::controller(UsuarioController::class)->group(function () {
        Route::post('/auth/login', "login");
        Route::post('/auth/logout', 'logout');
        Route::post('/auth/refresh','refresh');
        Route::get('/auth/isLogged','isLogged');
        Route::post("/auth/me", 'me');
        Route::post("/auth/changeMeProfileImage", 'changeMeProfileImage');
        Route::get('/usuarios', 'index');
        Route::post('/usuarios', 'nuevo');
        Route::get('/usuarios/{id}', 'buscar');
        Route::put('/usuarios/{id}', 'editar');
        Route::delete('/usuarios/{id}', 'eliminar');
        Route::post('/checkEmail', 'checkEmail');
        Route::post('/setPassword', 'setPassword');
        Route::post('/forgotPassword', 'forgotPassword');
        Route::get('/auth/notificaciones-alarmas', 'getNotificacionesAlarmas');
        Route::put('/auth/read-notificaciones-alarmas', 'readNotificacionesAlarmas');

    });

    /*USUARIO ENDPOINTS*/



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
        Route::get('/procesos/{id}/etapas', 'getEtapasByProcess');
        Route::get("/procesos/{id}/usuarios", 'getUsersByProcess');
        Route::get("/procesos/{id}/usuarios/notInProcess", 'getUsersNotInProcess');

        Route::post("/procesos/{id}/usuarios", 'addUsersToProcess');
        Route::delete("/procesos/{id}/usuarios/{userId}", 'removeUserFromProcess');
        Route::get('/procesos-usuario', 'getByUser');
        Route::get("/procesos/{userid}/procesos", 'getProcesosByUser');

    });

     /*ETAPA  ENDPOINTS*/
     Route::controller(EtapaController::class)->group(function () {
        Route::get('/etapas', 'list');
        Route::post('/etapas', 'create');
        Route::put('/etapas/{id}', 'update');
        Route::delete('/etapas/{id}', 'delete');
    });

    /*COMPONENTE  ENDPOINTS*/
    Route::controller(TipoComponenteController::class)->group(function () {
        Route::get('/tipos_componentes', 'list');
        Route::post('/tipos_componentes', 'create');
        Route::post('/tipos_componentes/{id}/edit', 'edit');
        Route::delete('/tipos_componentes/{id}', 'delete');

    });



     /*COMPONENTE  ENDPOINTS*/
     Route::controller(ComponenteController::class)->group(function () {
        Route::get('/componentes', 'list');
        Route::get('/componentes/prueba', 'prueba');
        Route::get('/componentes/{id}', 'getById');
        Route::post('/componentes', 'create');
        Route::post('/componentes/{id}', 'update');
        Route::delete('/componentes/{id}', 'delete');
        Route::get('/componentes/{componenteId}/imagenes/{imageId}', 'getImage');
        Route::post('/componentes/{id}/imagenes', 'addImageById');
        Route::delete('/componentes/{componenteId}/imagenes/{imageId}', 'removeImageById');
        Route::get('/componentes-sin-nodo', 'listarDispositivosSinNodo');
        Route::get('/componentes/{id}/marcaLast24Hours' , 'marcaLast24Hours');
        Route::post('/componentes/{id}/toggleOn' , 'toggleOn');


    });

    /*PARTE ENDPOINTS*/
    Route::controller(ParteController::class)->group(function () {
        Route::get('/componentes/{componenteId}/partes', 'list');
        Route::post('/componentes/{componenteId}/partes', 'create');
        Route::put('/componentes/{componenteId}/partes/{id}', 'update');
        Route::delete('/componentes/{componenteId}/partes/{id}', 'delete');
        Route::get('/componentes/{componenteId}/partes/{id}', 'getById');
        Route::post('/componentes/{componenteId}/partes/{id}/notas', 'addNota');
        Route::get('/componentes/{componenteId}/partes/{id}/notas', 'listNotas');
    });



    /*ALARMA ENDPOINTS*/
    Route::controller(AlarmaController::class)->group(function () {
        Route::get('/alarmas', 'list');
        Route::get("/alarmas/{id}/usuarios", 'getUsers');
        Route::post('/alarmas', 'create');
        Route::get('/alarmas-usuario', 'getByUser');
        Route::get('/componentes/{id}/marcaLast24Hours' , 'marcaLast24Hours');

    });

     /*NODOS ENDPOINTS*/
     Route::controller(NodoController::class)->group(function () {
        Route::post('/nodos', 'create');
        Route::delete('/nodos/{id}', 'delete');
        Route::put('/nodos/{id}/updatePosition', 'updateNodePosition');
        Route::delete('/nodosByComponente/{id}', 'deleteByComponentId');
        Route::get('/nodos/{etapaId}', 'list');
        Route::get('/nodos/nodo/{nodoId}', 'getById');
    });

     /*LINKS ENDPOINTS*/
     Route::controller(LinkController::class)->group(function () {
        Route::post('/links', 'create');
        Route::delete('/links/{id}', 'delete');
        Route::get('/links/{etapaId}', 'list');

    });

    /*REGISTROS ENDPOINTS*/
    Route::controller(RegistroController::class)->group(function () {
        Route::get('/componentes/{id}/registros', 'list');
        Route::get('/componentes/{id}/registros/exportExcel', 'exportExcel');

    });


     /*FTP ENDPOINTS*/
     Route::controller(FtpController::class)->group(function () {
        Route::get('/ftp/download', 'obtenerRegistros');
        Route::get("/ftp/realTime","realTime");
        Route::post('/ftp/nuevosRegistros', 'generarRegistros');
        Route::post('/ftp/last', 'last24Hour');
        Route::post("/ftp/writeXmlAlarm" ,"writeXmlAlarm");
        Route::get("/ftp/activityProcess" ,"activityProcess");

    });



    Route::get('/broadcast', function(Request $request){
        broadcast(new Hello());
    });

    Route::controller(EstadisticaController::class)->group(function () {
        Route::get('/estadisticas/cantidadUsuarios', 'countUsers');
        Route::get('/estadisticas/cantidadProcesos', 'countProcesos');
        Route::get('/estadisticas/cantidadComponentes', 'countComponentes');
        Route::get('/estadisticas/cantidadTipoComponentes', 'countTiposDeComponentes');
        Route::get('/estadisticas/cantidadComponentesPorProcesos', 'countComponentesPorProcesos');
        Route::get('/estadisticas/cantidadEtapasPorProcesos', 'countEtapasPorProcesos');
        Route::get('/estadisticas/actividadPorProcesos', 'actividadPorProcesos');

    });


    Route::controller(UnidadController::class)->group(function () {
        Route::get('/unidades', 'list');
    });

    Route::controller(ReporteController::class)->group(function(){
        Route::get("/reportes/procesosMasActivos", 'procesosMasActivos');
        Route::get("/reportes/alarmasPorDispositivos", "alarmasPorDispositivos");
        Route::get("/reportes/dispositivosMasActivos", "dispositivosMasActivos");
        Route::get("/reportes/alarmasPorProceso", "alarmasPorProceso");
        Route::get("/reportes/dispositivosActivosEInactivos", "dispositivosActivosEInactivos");
        Route::get("/reportes/dispositivosActivosPorProceso", "dispositivosActivosPorProceso");
        Route::get("/reportes/cantidadDispositivosPorTipo", "cantidadDispositivosPorTipo");
        Route::get("/reportes/cantidadAlarmasPorTipoDispositivo", "cantidadAlarmasPorTipoDispositivo");
    });


});
