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

}
