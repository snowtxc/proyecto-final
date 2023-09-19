<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoParte extends Model
{
    use HasFactory;

    protected $fillable = [
        'Nombre'
    ];

    public function partes()
    {
        return $this->hasMany(Parte::class);
    }

}
