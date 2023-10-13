<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alarma;
use App\Helpers\FileHelper;
use Carbon\Carbon;



class AlarmaController extends Controller
{
    public function list(Request $request){

        $page = ($request->query('page') != null && $request->query('page') >= 1) ? $request->query('page') : 1;
        $maxRows = $request->query('maxRows') != null ? $request->query('maxRows') : 10;
        $offset = $page == 1 ? 0 : (($page - 1) * $maxRows);

        $procesoId  = $request->query('proceso_id') != null ? $request->query('proceso_id') : null;
        $componenteId  = $request->query('componente_id') != null ? $request->query('componente_id') : null;
        $fechaInicio = $request->query('fechaInicio') != null ? $request->query('fechaInicio') : null;
        $fechaFin =  $request->query('fechaFin') != null ? $request->query('fechaFin') : null;



        $query  =  Alarma::when(isset($procesoId), function ($query) use ($procesoId) {
            $query->where('proceso_id', '=', $procesoId);
        })->when(isset($componenteId), function ($query) use ($componenteId) {
            $query->where('componente_id', '=', $componenteId);
        })->when(isset($fechaInicio) && isset($fechaFin), function ($query) use ($fechaInicio, $fechaFin) {
            $start =  Carbon::createFromFormat('d/m/Y', $fechaInicio);
            $end  =  Carbon::createFromFormat('d/m/Y', $fechaFin);
            $query->whereBetween('created_at', [$start, $end]);
        });

        $queryPaginated  = $query->skip($offset)->take($maxRows);



        $countRows =  $query->count();
        $alarmas = $queryPaginated->get();

        $result = array();
        foreach($alarmas as $alarma){
              $componente = $alarma->componente;
              $proceso =  $alarma->proceso;
              $tipoComponente = $componente->tipoComponente;
              $imagen = $tipoComponente->Imagen;
              $urlPath = FileHelper::getRealPath($imagen);

              array_push($result,
                    [
                        "id" => $alarma->id,
                        "componenteNombre" => $componente->Nombre,
                        "tipoComponente" => $tipoComponente->Nombre,
                        "procesoNombre" => $proceso->Nombre,
                        "fechaHora"  => $alarma->created_at,
                        "tipoComponenteImagen" => $urlPath
                    ]
                );


        }


        return array("data" => $result, "countRows" => $countRows);
    }

}
