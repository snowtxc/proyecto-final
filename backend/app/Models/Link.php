<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'nodo_from_id',
        'nodo_to_id',
        'etapa_id'
    ];


    public function fromNodo()
    {
        return $this->belongsTo(Nodo::class, 'nodo_from_id');
    }

    public function toNodo()
    {
        return $this->belongsTo(Nodo::class, 'nodo_to_id');
    }

    public function Etapa()
    {
        return $this->belongsTo(Etapa::class, 'etapa_id');
    }
}
