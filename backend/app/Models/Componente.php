<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Componente extends Model
{
    use HasFactory;

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Unidad',
        'DireccionIp',
        'etapa_id',
        "tipo_componente_id"
    ];

    public function partes()
    {
        return $this->hasMany(Parte::class);
    }

    public function etapa()
    {
        return $this->belongsTo(Etapa::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function imagenes()
    {
        return $this->hasMany(ComponenteImagen::class);
    }

    public function tipoComponente()
    {
        return $this->belongsTo(TipoComponente::class);
    }

    public function registros()
    {
        return $this->hasMany(Registro::class);
    }

    public function  nodo()
    {
        return $this->hasOne(Nodo::class);
    }

    public function alarmas(){
        return $this->hasMany(Alarma::class);
    }

}
