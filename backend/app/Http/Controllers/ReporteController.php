<?php

namespace App\Http\Controllers;
use PDF;
use View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function procesosMasActivos(Request $request){
        $startDate = $request->query('startDate') ?? null;
        $endDate = $request->query('endDate') ?? null;

        $result = DB::table('procesos')
            ->join('etapas', 'procesos.id', '=', 'etapas.proceso_id')
            ->join('registros', 'registros.etapa_id', '=', 'etapas.id');

         if ($startDate !== null) {
            $start = Carbon::createFromFormat('d/m/Y H:i', $startDate);
            $result->where('registros.created_at', '>=', $start);
        }

        if ($endDate !== null) {
            $end = Carbon::createFromFormat('d/m/Y H:i', $endDate);
            $result->where('registros.created_at', '<=', $end);
        }

        $result = $result->select(
                'procesos.id as id',
                'procesos.nombre as Nombre',
                DB::raw('COUNT(registros.id) as CantidadRegistros')
            )
            ->groupBy('procesos.id', 'procesos.nombre')
            ->get();

        return response()->json($result, 200);

    }

    public function alarmasPorDispositivos(Request $request){

        $startDate = $request->query('startDate') ?? null;
        $endDate = $request->query('endDate') ?? null;

        $result = DB::table('componentes')
        ->join('alarmas', 'alarmas.componente_id', '=', 'componentes.id');

        if ($startDate !== null) {
            $start = Carbon::createFromFormat('d/m/Y H:i', $startDate);
            $result->where('alarmas.created_at', '>=', $start);
        }

        if ($endDate !== null) {
            $end = Carbon::createFromFormat('d/m/Y H:i', $endDate);
            $result->where('alarmas.created_at', '<=', $end);
        }

        $result =  $result->select('componentes.id as id',  'componentes.Nombre as Nombre', DB::raw('COUNT(alarmas.id) as CantidadAlarmas'))
        ->groupBy('componentes.id', 'componentes.Nombre')
        ->get();

        return response()->json($result, 200);
    }

    public function dispositivosMasActivos(Request $request){
        $startDate = $request->query('startDate') ?? null;
        $endDate = $request->query('endDate') ?? null;

        $result = DB::table('componentes')
        ->join('registros', 'registros.componente_id', '=', 'componentes.id');

        if ($startDate !== null) {
            $start = Carbon::createFromFormat('d/m/Y H:i', $startDate);
            $result->where('registros.created_at', '>=', $start);
        }

        if ($endDate !== null) {
            $end = Carbon::createFromFormat('d/m/Y H:i', $endDate);
            $result->where('registros.created_at', '<=', $end);
        }

        $result = $result->select('componentes.id as id',  'componentes.Nombre as Nombre', DB::raw('COUNT(registros.id) as CantidadRegistros'))
        ->groupBy('componentes.id', 'componentes.Nombre')
        ->get();

        return response()->json($result, 200);

    }

    public function alarmasPorProceso(Request $request){
        $startDate = $request->query('startDate') ?? null;
        $endDate = $request->query('endDate') ?? null;

        $result = DB::table('procesos')
        ->join('alarmas', 'alarmas.proceso_id', '=', 'alarmas.id');

        if ($startDate !== null) {
            $start = Carbon::createFromFormat('d/m/Y H:i', $startDate);
            $result->where('alarmas.created_at', '>=', $start);
        }

        if ($endDate !== null) {
            $end = Carbon::createFromFormat('d/m/Y H:i', $endDate);
            $result->where('alarmas.created_at', '<=', $end);
        }

        $result = $result->select('procesos.id as id',  'procesos.Nombre as Nombre', DB::raw('COUNT(alarmas.id) as CantidadAlarmas'))
        ->groupBy('procesos.id', 'procesos.Nombre')
        ->get();

        return response()->json($result, 200);
    }

    public function dispositivosActivosEInactivos(){
        $cantDispositivos = DB::table('componentes')->select(DB::raw('COUNT(componentes.id) as CantDispositivos'))
        ->get()->first()->CantDispositivos;


        $cantNodos = DB::table('nodos')->select(DB::raw('COUNT(nodos.id) as CantNodos'))
        ->get()->first()->CantNodos;

        $DispositivosInactivos = $cantDispositivos - $cantNodos;
        $DispositivosActivos =  $cantNodos;

        return response()->json([
            "CantDispositivosOperativos" => $DispositivosActivos,
            "CantDispositivosInactivos" =>  $DispositivosInactivos
        ], 200);


    }

    public function dispositivosActivosPorProceso(){
        $result = DB::table('procesos')
        ->join('etapas', 'etapas.proceso_id', '=', 'procesos.id')
        ->join('nodos', 'nodos.etapa_id', '=', 'etapas.id')
        ->select('procesos.id as id',  'procesos.Nombre as Nombre', DB::raw('COUNT(nodos.id) as CantDispositivosActivos'))
        ->groupBy('procesos.id', 'procesos.Nombre')
        ->get();

        return response()->json($result, 200);
    }

    public function cantidadDispositivosPorTipo(){
        $result = DB::table('tipo_componentes')
        ->join('componentes', 'tipo_componentes.id', '=', 'componentes.tipo_componente_id')
        ->select('tipo_componentes.id as id',  'tipo_componentes.Nombre as Nombre', DB::raw('COUNT(componentes.id) as CantDispositivos'))
        ->groupBy('tipo_componentes.id', 'tipo_componentes.Nombre')
        ->get();

        return response()->json($result, 200);

    }

    public function cantidadAlarmasPorTipoDispositivo(){
        $result = DB::table('tipo_componentes')
        ->join('componentes', 'tipo_componentes.id', '=', 'componentes.tipo_componente_id')
        ->join('alarmas', 'alarmas.componente_id', '=', "componentes.id")
        ->select('tipo_componentes.id as id',  'tipo_componentes.Nombre as Nombre', DB::raw('COUNT(alarmas.id) as CantAlarmas'))
        ->groupBy('tipo_componentes.id', 'tipo_componentes.Nombre')
        ->get();

        return response()->json($result, 200);


    }




}
