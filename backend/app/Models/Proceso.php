<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
use HasFactory;
    protected $fillable = [
        'Nombre',
        'Descripcion',
    ];

    public function etapas()
    {
        return $this->hasMany(Etapa::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'proceso_user');
    }

    public function alarmas(){
        return $this->hasMany(Alarma::class);
    }


}
