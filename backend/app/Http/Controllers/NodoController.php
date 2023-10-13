<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Models\Nodo;
use App\Models\Componente;
use App\Models\Etapa;
class NodoController extends Controller
{


    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'Posicion' => 'required|numeric',
            "componente_id"  => 'required|numeric',
            "etapa_id"  => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $body =  $request->all();
        $componente  = Componente::find($body['componente_id']);
        if(!isset($componente)){
            return response()->json(['error' => 'Componente no encontrado'], 404);
        }

        $etapa  = Etapa::find($body['etapa_id']);
        if(!isset($etapa)){
            return response()->json(['error' => 'Etapa no encontrada'], 404);
        }

        $nodo = Nodo::where('componente_id', "=", $componente->id)->first();
        if(isset($nodo)){
            return response()->json(['error' => 'Ya existe un nodo con ese componente'], 404);
        }
        $nodoCreated = Nodo::create($request->all());

        return response()->json($nodoCreated, 200);



    }


    public function delete($id){
        $nodo = Nodo::find($id);
        if(!isset($nodo)){
            return response()->json(['error' => 'Nodo no encontrado'], 404);
        }
        $nodo->delete();
        return response()->json(['message' => 'Nodo eliminado'], 200);
    }


}
