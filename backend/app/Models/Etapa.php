<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    use HasFactory;
    protected $fillable = [
        'Nombre',
        "proceso_id"
    ];

    public function proceso()
    {
        return $this->belongsTo(Proceso::class);
    }

    public function componentes()
    {
        return $this->hasMany(Componente::class);
    }

}
