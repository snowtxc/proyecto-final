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
    public function list(Request $request){

        $page = ($request->query('page') != null && $request->query('page') >= 1) ? $request->query('page') : 1;
        $maxRows = $request->query('maxRows') != null ? $request->query('maxRows') : 10;
        $offset = $page == 1 ? 0 : (($page - 1) * $maxRows);

        $nombre = $request->query('nombre') != null ? $request->query('nombre') : null;

        $countRows = Componente::when(isset($nombre), function ($query) use ($nombre) {
            $query->where('Nombre', 'like', '%' . $nombre . '%');
        })->count();

        $componentes  = Componente::skip($offset)->take($maxRows)->when(isset($nombre), function ($query) use ($nombre) {
            $query->where('Nombre', 'like', '%' . $nombre . '%');
        })->get();

        $result = array();
        foreach($componentes as $componente){
            $tipoComponente = $componente->tipoComponente;
            $pathImage =  env('APP_URL').":".env("APP_PORT").FileHelper::getRealPath($tipoComponente->Imagen);
            array_push($result,
               [
                "tipoComponenteImage" => $pathImage,
                "tipoComponenteNombre" => $tipoComponente->Nombre,
                "Nombre" => $componente->Nombre,
                "Descripcion" => $componente->Descripcion,
                "Unidad" => $componente->Unidad,
                "DireccionIp" => $componente->DireccionIp,
                "etapa_id" => $componente->etapa_id,
                "tipo_componente_id" => $componente->tipo_componente_id,
                "id" => $componente->id
               ]
            );
        }
        return array("data" => $result, "countRows" => $countRows);
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            "etapa_id" => "required|integer",
            "DireccionIp" => "required|Ipv4",
            "Descripcion" => "required",
            "tipo_componente_id" => "required",
            'imagenes.*' => 'file'
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

        if(isset($body['imagenes'])){
           $imagenes = $body['imagenes'];
           foreach($imagenes as $imagen){
                $fileName = uniqid() . '.' . $imagen->getClientOriginalExtension();
                $pathUploaded = FileHelper::uploadFile($imagen, 'public',$fileName);
                $componente->imagenes()->create([
                    'Path' => $pathUploaded,
                    'Nombre' => $fileName
                ]);
              }
        }
        return $componente;
    }

    public function getById($id){
        $componente = Componente::find($id);
        if(isset($componente)){
            $etapa = $componente->etapa;
            $tipoComponente = $componente->tipoComponente;
            $imagenes = $componente->imagenes;
            $imagenesPath = [];
            foreach($imagenes as $imagen){
                $pathImage =  env('APP_URL').":".env("APP_PORT").FileHelper::getRealPath($imagen->Path);
                array_push($imagenesPath, $pathImage);
            }
            $proceso_id = $etapa->proceso_id;
            $pathImage =  env('APP_URL').":".env("APP_PORT").FileHelper::getRealPath($tipoComponente->Imagen);
            return [
                "tipoComponenteImage" => $pathImage,
                "tipoComponenteNombre" => $tipoComponente->Nombre,
                "Nombre" => $componente->Nombre,
                "Descripcion" => $componente->Descripcion,
                "Unidad" => $componente->Unidad,
                "DireccionIp" => $componente->DireccionIp,
                "etapa_id" => $componente->etapa_id,
                "proceso_id" => $etapa->proceso_id,
                "tipo_componente_id" => $componente->tipo_componente_id,
                "id" => $componente->id,
                "imagenes" => $imagenesPath
            ];
        }
        return response()->json(['error' => 'Componente no encontrado'], 404);
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
