<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Componente;
use App\Models\Etapa;
use App\Models\ComponenteImagen;
use App\Helpers\FileHelper;

use Validator;

class ComponenteController extends Controller
{
    public function list(){
        return Componente::all();
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            "grupo_id" => "",
            "etapa_id" => "required|integer"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $body = $request->all();

        $etapa = Etapa::find($body['etapa_id']);
        if(!isset($etapa)){
            return response()->json(['error' => 'Etapa no existe'], 404);

        }
        $componente = Componente::create($body);
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
        if(!isset($componente)){
            return response()->json(['error' => 'Componente no encontrado'], 404);
        }
        $body = $request->all();
        $file = $body['Imagen'];

        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $pathUploaded = FileHelper::uploadFile($file, 'public',$fileName);

        $imageRow =  $componente->imagenes()->create([
            'Path' => $pathUploaded,
            'Nombre' => $fileName
        ]);


        return response()->json(['ok' => $imageRow], 200);
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
        FileHelper::deleteFile($image->Path);
        return response()->json(['success' => 'Imagen de Componente borrada'], 200);

    }


    public function getImage($componenteId, $imageId){
        $componente = Componente::find($componenteId);
        if(!isset($componente)){
            return response()->json(['error' => 'Componente no encontrado'], 404);
        }
        $image = $componente->imagenes()->find($imageId);
        if(!isset($image)){
            return response()->json(['error' => 'Imagen de Componente no encontrado'], 404);
        }
        $path = FileHelper::getRealPath($image->Path);
        $fullPath =  env('APP_URL').":".env("APP_PORT").$path;
        return response()->json(['path' => $fullPath], 200);

    }
}
