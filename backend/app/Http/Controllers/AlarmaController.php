<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alarma;


class AlarmaController extends Controller
{
    public function list(Request $request){

        $page = ($request->query('page') != null && $request->query('page') >= 1) ? $request->query('page') : 1;
        $maxRows = $request->query('maxRows') != null ? $request->query('maxRows') : 10;
        $offset = $page == 1 ? 0 : (($page - 1) * $maxRows);

        $procesoId  = $request->query('proceso_id') != null ? $request->query('proceso_id') : null;
        $componenteId  = $request->query('componente_id');
        $fechaInicio = $request->query('fechaInicio') != null ? $request->query('fechaInicio') : null;
        $fechaFin =  $request->query('fechaFin') != null ? $request->query('fechaFin') : null;

        $countRows = Alarma::when(isset($procesoId), function ($query) use ($procesoId) {
            $query->where('proceso_id', '=', $procesoId);
        })->count();

        $alarmas = Alarma::skip($offset)->take($maxRows)->when(isset($procesoId), function ($query) use ($procesoId) {
            $query->where('proceso_id', '='  ,$procesoId );
        })->get();

        $result = array();
        foreach($alarmas as $alarma){
              $componente = $alarma->componente;
              $tipoComponente = $componente->tipo_componente;
              return array("data" => $tipoComponente, "countRows" => $countRows);

        }

        return array("data" => $alarmas, "countRows" => $countRows);
    }

}
