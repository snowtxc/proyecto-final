<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Proceso;
use App\Models\Componente;
use App\Models\TipoComponente;
use Illuminate\Support\Facades\DB;
use App\Utils\Helper;




class EstadisticaController extends Controller
{
    public function countUsers(){
        $count = User::count();
        return response()->json($count, 200);
    }


    public function countProcesos(){
        $count = Proceso::count();
        return response()->json($count, 200);
    }

    public function countComponentes(){
        $count = Componente::count();
        return response()->json($count, 200);
    }


    public function countTiposDeComponentes(){
        $count = TipoComponente::count();
        return response()->json($count, 200);
    }


    public function countComponentesPorProcesos(){
        $result = DB::table('procesos')
            ->join('etapas', 'procesos.id', '=', 'etapas.proceso_id')
            ->join('nodos', 'etapas.id', '=', 'nodos.etapa_id')
            ->select('procesos.id as id',  'procesos.nombre as Nombre', DB::raw('COUNT(nodos.id) as CantidadNodos'))
            ->groupBy('procesos.id', 'procesos.nombre')
            ->get();

        return response()->json($result, 200);

    }


    public function countEtapasPorProcesos(){
        $result = DB::table('procesos')
            ->join('etapas', 'procesos.id', '=', 'etapas.proceso_id')
            ->select('procesos.id as id',  'procesos.nombre as Nombre', DB::raw('COUNT(etapas.id) as CantidadEtapas'))
            ->groupBy('procesos.id', 'procesos.nombre')
            ->get();

        return response()->json($result, 200);

    }

    public function actividadPorProcesos(){
        $result = Helper::processesActivityLastHour();
        return response()->json($result, 200);
    }


}
