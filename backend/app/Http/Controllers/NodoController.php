<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Models\Nodo;
use App\Models\Componente;
use App\Models\Etapa;


//events
use App\Events\NodePositionUpdated;



class NodoController extends Controller
{


    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'Posicion' => 'required',
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

    public function deleteByComponentId($componenteId){
        $nodos = Nodo::where('componente_id', $componenteId)->get();
    
        if($nodos->isEmpty()){
            return response()->json(['error' => 'Nodo(s) no encontrado(s) para el componente_id especificado'], 404);
        }
    
        foreach ($nodos as $nodo) {
            $nodo->delete();
        }
    
        return response()->json(['message' => 'Nodo(s) eliminado(s) para el componente_id especificado'], 200);
    }

    public function updateNodePosition(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'NewPosition' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $nodo = Nodo::find($id);
        if(!isset($nodo)){
            return response()->json(['error' => 'Nodo no encontrado'], 404);
        }

        $body = $request->all();
        $newPosition = $body = ['NewPosition'];

        $etapa  = $nodo->etapa;
        $etapaId = $nodo->etapa_id;
        $procesoId = $etapa->proceso_id;
        $nodo->Posicion = $newPosition;
        $nodo->save();

        broadcast(new NodePositionUpdated($nodo->id, $newPosition, $procesoId,$etapaId));

    }

    public function list($etapaId) {
        $nodos = Nodo::where('etapa_id', $etapaId)->get();
        return response()->json($nodos, 200);
    }


}
