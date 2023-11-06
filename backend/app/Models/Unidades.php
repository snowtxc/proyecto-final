<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidades extends Model
{
    use HasFactory;

    public function componentes(){
        return $this->belongsToMany(Componente::class, 'componente_unidads');
    }

    public function registros(){
        return $this->hasMany(Registro::class);

    }



}
