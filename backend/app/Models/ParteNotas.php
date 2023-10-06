<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParteNotas extends Model
{
    use HasFactory;
    protected $fillable = [
        'Descripcion',
        'user_id',
        'parte_id'
    ];

    public function  parte()
    {
        return $this->belongsTo(Parte::class);
    }


    public function  user()
    {
        return $this->belongsTo(User::class);
    }


}
