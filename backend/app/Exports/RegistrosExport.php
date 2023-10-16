<?php

namespace App\Exports;

use App\Models\Registro;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;


class RegistrosExport implements FromCollection
{
    private $componenteId;
    private $fechaInicio;
    private $fechaFin;


    public function __construct($componenteId,$fechaInicio,$fechaFin)
    {
        $this->componenteId = $componenteId;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }


    public function collection()
    {
        $COMPONENTE_ID  = $this->componenteId;
        $FECHA_INICIO =  $this->fechaInicio;
        $FECHA_FIN = $this->fechaFin;

        $registros  = Registro::where("componente_id", "=", $COMPONENTE_ID)->when(isset($FECHA_INICIO) , function ($query) use ($FECHA_INICIO) {
            $start = Carbon::createFromFormat('d/m/Y H:i', $FECHA_INICIO);
            $query->whereDate('created_at', ">=" , $start);
        })->when(isset($FECHA_FIN), function($query) use ($FECHA_FIN){
            $end = Carbon::createFromFormat('d/m/Y H:i', $FECHA_FIN);
            $query->whereDate('created_at', "<=" , $end);
        })->orderBy('created_at','desc')->get();

        $result = array();

        array_push($result,[
            "Fecha y Hora",
            "Marca",
            "Proceso",
            "Etapa",
        ]);
        foreach($registros as $registro){
            $etapa = $registro->etapa;
            $proceso =  $etapa->proceso;

            $etapaNombre = $etapa->Nombre;
            $procesoNombre = $proceso->Nombre;

            array_push($result,
                [
                "fechaHora" => $registro->created_at,
                "marca"  => $registro->Marca,
                "proceso" => $procesoNombre,
                "etapa"  => $etapaNombre
                ]
             );


        }

        return collect($result);
    }
}
