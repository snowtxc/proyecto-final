<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parte extends Model
{
    use HasFactory;

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Imagen',
        'componente_id'
    ];



    public function  componente()
    {
        return $this->belongsToMany(Componente::class);
    }
}
