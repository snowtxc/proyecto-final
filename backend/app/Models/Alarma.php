<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alarma extends Model
{
    use HasFactory;

    public function users(){
        return $this->belongsToMany(User::class, 'alarma_users');
    }

    public function proceso(){
        return $this->belongsTo(Proceso::class);
    }

    public function componente(){
        return $this->belongsTo(Componente::class);

    }

}
