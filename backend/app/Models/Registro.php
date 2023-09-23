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
    ];

    public function componente()
    {
        return $this->belongsTo(Componente::class);
    }


}
