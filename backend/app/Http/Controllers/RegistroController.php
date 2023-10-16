<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use App\Models\Componente;
use Carbon\Carbon;


use App\Exports\RegistrosExport;
use Maatwebsite\Excel\Facades\Excel;

class RegistroController extends Controller
{
   public function list(Request $request, $componenteId){
        $page = ($request->query('page') != null && $request->query('page') >= 1) ? $request->query('page') : 1;
        $maxRows = $request->query('maxRows') != null ? $request->query('maxRows') : 10;
        $offset = $page == 1 ? 0 : (($page - 1) * $maxRows);
        $fechaInicio = $request->query('fechaInicio') != null ? $request->query('fechaInicio') : null;
        $fechaFin =  $request->query('fechaFin') != null ? $request->query('fechaFin') : null;


        $componente = Componente::find($componenteId);
        if(!isset($componente)){
            return response()->json(['error' => 'Componente no existe'], 404);
        }

        $query = Registro::where("componente_id", "=", $componenteId)->when(isset($fechaInicio) , function ($query) use ($fechaInicio) {
            $start = Carbon::createFromFormat('d/m/Y H:i', $fechaInicio);
            $query->whereDate('created_at', ">=" , $start);
        })->when(isset($fechaFin), function($query) use ($fechaFin){
            $end = Carbon::createFromFormat('d/m/Y H:i', $fechaFin);
            $query->whereDate('created_at', "<=" , $end);
        })->orderBy('created_at','desc');

        $countRows =  $query->count();
        $registros =  $query->skip($offset)->take($maxRows)->get();


        $result = [];
        foreach($registros as $registro){
            $etapa = $registro->etapa;
            $proceso =  $etapa->proceso;

            $etapaNombre = $etapa->Nombre;
            $procesoNombre = $proceso->Nombre;

            array_push($result,
                [
                "fechaHora" => $registro->created_at,
                "marca"  => $registro->Marca,
                "unidad" => "°C",
                "proceso" => $procesoNombre,
                "etapa"  => $etapaNombre
                ]
             );
        }
        return response()->json(["countRows" => $countRows, "data" => $result], 200);



   }

   public function exportExcel(Request $request,$componenteId){
      $fechaInicio = $request->query('fechaInicio') != null ? $request->query('fechaInicio') : null;
      $fechaFin =  $request->query('fechaFin') != null ? $request->query('fechaFin') : null;
      return Excel::download(new RegistrosExport($componenteId,$fechaInicio,$fechaFin), 'historicos.xlsx');
   }
}
