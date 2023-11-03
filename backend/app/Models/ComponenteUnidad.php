<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponenteUnidad extends Model
{
    use HasFactory;
    protected $fillable = [
        'unidad_id',
        'componente_id',
        'min',
        'max'
    ];
}
