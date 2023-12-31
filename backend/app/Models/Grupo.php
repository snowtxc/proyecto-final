<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;
    protected $fillable = [
        'Nombre'
    ];

    public function  componentes()
    {
        return $this->hasMany(Componente::class);
    }



}
