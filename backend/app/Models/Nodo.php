<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nodo extends Model
{
    use HasFactory;

    protected $fillable = [
        'Posicion',
        'componente_id',
        'etapa_id'
    ];


    public function  componente()
    {
        return $this->hasOne(Componente::class);
    }

    public function etapa(){
        return $this->belongsTo(Etapa::class);
    }


    public function nodosFroms(){
        return $this->hasMany(Link::class, 'nodo_from_id');
    }

    public function nodosTos(){
        return $this->hasMany(Link::class, 'nodo_to_id');
    }

}
