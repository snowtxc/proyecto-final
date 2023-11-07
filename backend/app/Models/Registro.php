<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;

    protected $fillable = [
        'FechaHora',
        'Marca',
        'etapa_id',
        'componente_id',
        'unidad_id'
    ];

    public function componente()
    {
        return $this->belongsTo(Componente::class);
    }

    public function unidad()
    {
        return $this->belongsTo(Unidades::class);
    }


    public function etapa()
    {
        return $this->belongsTo(Etapa::class);
    }

}
