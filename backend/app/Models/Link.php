<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    public function fromNodo()
    {
        return $this->belongsTo(Nodo::class, 'nodo_from_id');
    }

    public function toNodo()
    {
        return $this->belongsTo(Nodo::class, 'nodo_to_id');
    }
}
