<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parte;
use App\Models\TipoParte;
use App\Models\Componente;
use Validator;


class ParteController extends Controller
{

    public function list(Request $request){
       return Parte::all();
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            'Unidad' => 'required',
            'DireccionIp' => 'required|ip',
            'Descripcion' => 'required',
            'Imagen' => 'required',
            "componente_id"  => 'required|numeric',
            'tipo_parte_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $body =  $request->all();
        $componente =  Componente::find($body['componente_id']);
        if(!isset($componente)){
            return response()->json(['error' => 'Componente no encontrado'], 404);
        }

        $tipoParte = TipoParte::find($body['tipo_parte_id']);
        if(!isset($tipoParte)){
            return response()->json(['error' => 'Tipo parte no encontrada'], 404);
        }
        $parte = Parte::create($request->all());
        return $parte;
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            'Unidad' => 'required',
            'DireccionIp' => 'required|ip',
            'Descripcion' => 'required',
            'Imagen' => 'required',
            "componente_id"  => 'required|numeric',
            'tipo_parte_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $parte = Parte::find($id);
        if(isset($parte)){
            $parte->update($request->all());
            return $etapa;
        }
        return response()->json(['error' => 'Parte no encontrada'], 404);
    }


    public function delete($id){
        $parte = Parte::find($id);
        if(isset($parte)){
            $parte->delete();
            return response()->json(['success' => 'Parte borrada'], 200);
        }
        return response()->json(['error' => 'Parte no encontrada'], 404);
    }

}
