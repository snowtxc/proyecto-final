<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponenteImagen extends Model
{
    use HasFactory;

    protected $fillable = [
        'Nombre',
        'Path',
        'Extension'
    ];

    public function componente()
    {
        return $this->belongsTo(Componente::class);
    }
}
