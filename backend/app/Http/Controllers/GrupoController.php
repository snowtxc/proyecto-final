<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;
use Validator;


class GrupoController extends Controller
{
    public function list(){
        return Grupo::all();
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $group = Grupo::create($request->all());
        return $group;
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $group = Grupo::find(1);
        if(isset($group)){
            $group->update($request->all());
            return $group;
        }
        return response()->json(['error' => 'Grupo no encontrado'], 404);

    }


    public function delete($id){
        $group = Grupo::find(1);
        if(isset($group)){
            $group->delete();
            return response()->json(['success' => 'Grupo borrado'], 200);
        }
        return response()->json(['error' => 'Grupo no encontrado'], 404);

    }




}
