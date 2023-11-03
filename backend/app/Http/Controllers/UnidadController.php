<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unidades;




class UnidadController extends Controller{

    public function list(){
       $unidades = Unidades::all();
       return response()->json($unidades,200);

    }
}
