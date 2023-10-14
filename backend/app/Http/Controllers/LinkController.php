<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;

use App\Events\LinkCreated;

use Validator;


class LinkController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'nodo_from_id' => 'required|numeric',
            'nodo_to_id' => 'required|numeric',
            'etapa_id' => 'required|numeric',
            'proceso_id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $body = $request->all();
        if($body['nodo_from_id'] == $body["nodo_to_id"]){
            return response()->json(["message" => "Un nodo no puede estar relacionado a si mismo"],400);

        }
        $link =  Link::where("nodo_from_id" , "=", $body['nodo_from_id'])->where("nodo_to_id","=", $body['nodo_to_id'])->first();
        if(isset($link)){
              return response()->json(["message"=> "Ya existe un link entre el nodo con id: " . $body['nodo_from_id']. " al nodo con id " .  $body['nodo_to_id']],400);
        }
        $linkCreated = Link::create($request->all());
        broadcast(new LinkCreated($linkCreated->nodo_from_id, $linkCreated->nodo_to_id, $body["etapa_id"] ,$body["proceso_id"]));

        return $linkCreated;
    }


    public function delete(Request $request, $id){
        $link = Link::find($id);
        if(!isset($link)){
            return response()->json(["message" => "Link no existe"],404);
        }
        $link->delete();
        return response()->json($link,404);
    }
}
