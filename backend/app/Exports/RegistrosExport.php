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

        $registros  = Registro::select(
            'procesos.Nombre as proceso_nombre',
            'etapas.Nombre as etapa_nombre',
            'registros.created_at as fecha_registro',
            'registros.Marca'
        )
        ->join('etapas', 'registros.etapa_id', '=', 'etapas.id')
        ->join('procesos', 'etapas.proceso_id', '=', 'procesos.id')->where("componente_id", "=", $COMPONENTE_ID)->when(isset($FECHA_INICIO) , function ($query) use ($FECHA_INICIO) {
            $start = Carbon::createFromFormat('d/m/Y H:i', $FECHA_INICIO);
            $query->whereDate('created_at', ">=" , $start);
        })->when(isset($FECHA_FIN), function($query) use ($FECHA_FIN){
            $end = Carbon::createFromFormat('d/m/Y H:i', $FECHA_FIN);
            $query->whereDate('created_at', "<=" , $end);
        })->orderBy('registros.created_at','desc')->get();


        return collect($registros);
    }
}
