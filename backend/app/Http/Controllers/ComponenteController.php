<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Componente;
use App\Models\ComponenteImagen;

use Validator;

class ComponenteController extends Controller
{
    public function list(){
        return Componente::all();
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            'Descripcion' => ['required','min:10'],
            "grupo_id" => "",
            "etapa_id" => "required|integer"

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $componente = Componente::create($request->all());
        return $componente;
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            'Descripcion' => ['required','min:10'],
            "grupo_id" => "required|exists:procesos,id",
            "etapa_id" => "required|integer"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $componente = Componente::find($id);
        if(isset($group)){
            $componente->update($request->all());
            return $componente;
        }
        return response()->json(['error' => 'Componente no encontrado'], 404);
    }


    public function delete($id){
        $componente = Componente::find($id);
        if(isset($componente)){
            $componente->delete();
            return response()->json(['success' => 'Componente borrado'], 200);
        }
        return response()->json(['error' => 'Componente no encontrado'], 404);
    }

    public function addImageById(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'Imagen' => 'required|file'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $componente = Componente::find($id);
        $bodyPrueba =  [
            "Nombre" => "imagen",
            "Path" =>  "/path/imagen.jpg"
        ];

        if(isset($componente)){
            $imagen = $componente->imagenes()->create($bodyPrueba);  //crea una nueva imagen asociada al componente
            return $imagen;
        }
        return response()->json(['error' => 'Componente no encontrado'], 404);

    }

    public function removeImageById($componenteId, $imageId){
        $componente = Componente::find($componenteId);
        if(!isset($componente)){
            return response()->json(['error' => 'Componente no encontrado'], 404);
        }
        $image = $componente->imagenes()->find($imageId);
        if(!isset($image)){
            return response()->json(['error' => 'Imagen de Componente no encontrado'], 404);
        }
        $image->delete();
        return response()->json(['success' => 'Imagen de Componente borrada'], 200);

    }
}
