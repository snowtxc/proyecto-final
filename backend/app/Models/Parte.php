<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parte extends Model
{
    use HasFactory;

    protected $fillable = [
        'Nombre',
        'Unidad',
        'DireccionIp',
        'Descripcion',
        'Imagen',
        'tipo_parte_id'
    ];

    public function tipoParte()
    {
        return $this->belongsTo(TipoParte::class);
    }

    public function registros()
    {
        return $this->hasMany(Registro::class);
    }

    public function  componente()
    {
        return $this->belongsToMany(Componente::class);
    }
}
