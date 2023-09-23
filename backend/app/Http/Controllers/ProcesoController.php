<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proceso;
use Validator;

class ProcesoController extends Controller
{
    public function list(){
        return Proceso::all();
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            'Descripcion' => ['required','min:10']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $process = Proceso::create($request->all());
        return $process;
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            'Descripcion' => ['required','min:10'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $process = Proceso::find($id);
        if(isset($process)){
            $process->update($request->all());
            return $process;
        }
        return response()->json(['error' => 'Proceso no encontrado'], 404);

    }


    public function delete($id){
        $process =  Proceso::find($id);
        if(isset($process)){
            $process->delete();
            return response()->json(['success' => 'Proceso borrado'], 200);
        }
        return response()->json(['error' => 'Proceso no encontrado'], 404);
    }

}
