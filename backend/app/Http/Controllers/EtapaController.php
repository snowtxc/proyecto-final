<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Etapa;
use Validator;

class EtapaController extends Controller
{
    public function list(){
        return Etapa::all();
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            "proceso_id" => "required|exists:procesos,id"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $etapa = Etapa::create($request->all());
        return $etapa;
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            "proceso_id" => "required|exists:procesos,id"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $etapa = Etapa::find($id);

        if(isset($group)){
            $etapa->update($request->all());
            return $etapa;
        }
        return response()->json(['error' => 'Etapa no encontrada'], 404);
    }


    public function delete($id){
        $etapa = Etapa::find($id);
        if(isset($etapa)){
            $etapa->delete();
            return response()->json(['success' => 'Etapa borrada'], 200);
        }
        return response()->json(['error' => 'Etapa no encontrada'], 404);
    }
}
