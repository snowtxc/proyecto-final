<?php

namespace App\Utils;
use Illuminate\Support\Facades\DB;


class Helper
{

    //lista los procesos con la actividad de las ultima hora que equivale a la cantidad de registros generados por sus componentes
    public static function processesActivityLastHour()
    {
        $result = DB::table('procesos')
        ->leftJoin('etapas', 'procesos.id', '=', 'etapas.proceso_id')
        ->leftJoin('registros', 'etapas.id', '=', 'registros.etapa_id')
        ->where(function ($query) {
            $query->where('registros.created_at', '>=', now()->subHour())
                  ->orWhereNull('registros.created_at');
        })
        ->select('procesos.id as id', 'procesos.nombre as Nombre', DB::raw('COUNT(registros.id) as CantidadRegistros'))
        ->groupBy('procesos.id', 'procesos.nombre')
        ->get();

        return $result;
    }
}


