<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Componente;
use App\Models\Etapa;
use App\Models\ComponenteImagen;
use App\Models\TipoComponente;
use App\Models\ComponenteUnidad;
use App\Models\Registro;

use Illuminate\Support\Facades\DB;

use App\Helpers\FileHelper;

use App\Events\componenteAdded;
use App\Events\componenteDeleted;


use Validator;

class ComponenteController extends Controller
{

    public function list(Request $request){

        $page = ($request->query('page') != null && $request->query('page') >= 1) ? $request->query('page') : 1;
        $maxRows = $request->query('maxRows') != null ? $request->query('maxRows') : 10;
        $offset = $page == 1 ? 0 : (($page - 1) * $maxRows);

        $nombre = $request->query('nombre') != null ? $request->query('nombre') : null;
        $tipo = $request->query('tipo') != null ? $request->query('tipo') : null;

        $countRows = Componente::when(isset($nombre), function ($query) use ($nombre) {
            $query->where('Nombre', 'like', '%' . $nombre . '%');
        })->when(isset($tipo), function ($query) use ($tipo) {
            $query->where('tipo_componente_id', '=', $tipo );})->count();

        $componentes  = Componente::skip($offset)->take($maxRows)->when(isset($nombre), function ($query) use ($nombre) {
            $query->where('Nombre', 'like', '%' . $nombre . '%');
        })->when(isset($tipo), function ($query) use ($tipo) {
            $query->where('tipo_componente_id', '=', $tipo );
        })->get();

        $result = array();
        foreach($componentes as $componente){
            $tipoComponente = $componente->tipoComponente;
            $pathImage =  FileHelper::getRealPath($tipoComponente->Imagen);
            $unidades = $componente->unidades;
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
                "id" => $componente->id,
                "unidades" => $unidades
               ]
            );
        }
        return array("data" => $result, "countRows" => $countRows);
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            "DireccionIp" => "required|Ipv4",
            "Descripcion" => "required",
            "tipo_componente_id" => "required",
            "unidades" => "required",
            'imagenes.*' => 'file'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $body = $request->all();
        $unidades = $body['unidades'];

        $tipoComponente = TipoComponente::find($body['tipo_componente_id']);
        if(!isset($tipoComponente)){
            return response()->json(['error' => 'Tipo de Componente no existe'], 404);
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
        foreach($unidades as $unidad){
            $unidad_id = $unidad['unidad_id'];
            $min = $unidad['min'];
            $max = $unidad['max'];
            $unidadComponente = new ComponenteUnidad;
            $unidadComponente->unidades_id = $unidad_id;
            $unidadComponente->componente_id = $componente->id;
            $unidadComponente->min = $min;
            $unidadComponente->max = $max;
            $unidadComponente->save();
        }

        broadcast(new componenteAdded());
        return $componente;
    }

    public function getById($id){
        $componente = Componente::find($id);
        if(isset($componente)){
            $tipoComponente = $componente->tipoComponente;
            $nodo = $componente->nodo;
            $imagenes = $componente->imagenes;
            $imagenesPath = [];
            foreach($imagenes as $imagen){
                $pathImage =  FileHelper::getRealPath($imagen->Path);
                array_push($imagenesPath,
                    ["id" => $imagen->id,
                    "Path" => $pathImage,
                    "Nombre" => $imagen->Nombre]);
            }
            $pathImage =  FileHelper::getRealPath($tipoComponente->Imagen);
            $unidades = array();
            foreach($componente->unidades as $unidad){
                    array_push($unidades, [
                        "unidad_id" => $unidad->id,
                        "unidad" => $unidad->unidad,
                        "min" => $unidad->pivot->min,
                        "max" => $unidad->pivot->max,
                        "nombre" => $unidad->nombre
                    ]);
            }
            $nodoInfo = null;
            if(isset($nodo)){
                $etapa = $nodo->etapa;
                $nodoInfo =  [
                    "fechaDeIngreso" => $nodo->created_at,
                    "fechaDeActualizacion" => $nodo->updated_at,
                    "etapaId" => $etapa->id,
                    "etapa" => $etapa->Nombre,
                    "procesoId" => $etapa->proceso->id,
                    "proceso" => $etapa->proceso->Nombre,
                ];
            }
            return [
                "tipoComponenteImage" => $pathImage,
                "tipoComponenteNombre" => $tipoComponente->Nombre,
                "Nombre" => $componente->Nombre,
                "Descripcion" => $componente->Descripcion,
                "Unidad" => $componente->Unidad,
                "DireccionIp" => $componente->DireccionIp,
                "On" => $componente->On == 1 ? true : false,
                "etapa_id" => $componente->etapa_id,
                "proceso_id" =>  isset($etapa) ?  $etapa->proceso_id : null,
                "tipo_componente_id" => $componente->tipo_componente_id,
                "id" => $componente->id,
                "imagenes" => $imagenesPath,
                "unidades" => $unidades,
                "nodoInfo" => $nodoInfo
            ];
        }
        return response()->json(['error' => 'Componente no encontrado'], 404);
    }

    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            'Descripcion' => 'required',
            "tipo_componente_id" => 'required',
            "unidades.*" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $unidades = $request->all()['unidades'];
        $componente = Componente::find($id);
        if(isset($componente)){
            $unidadesComponente = ComponenteUnidad::where(["componente_id" => $componente->id])->get();

            foreach($unidades as $unidad){
                $unidad_id = intval($unidad['unidad_id']);
                $find = false;

                for($i = 0; $i < count($unidadesComponente); $i++){
                    if($unidadesComponente[$i]->unidades_id == $unidad_id){
                        $find = true;
                        $index = $i;
                        break;
                    }
                }

                if(!$find){
                    $newUnidadComponente = new ComponenteUnidad;
                    $newUnidadComponente->unidades_id = $unidad_id;
                    $newUnidadComponente->componente_id = $componente->id;
                    $newUnidadComponente->min = $unidad['min'];
                    $newUnidadComponente->max = $unidad['max'];
                    $newUnidadComponente->save();
                }else{
                    $componenteUnidad = ComponenteUnidad::where(["unidades_id"=> $unidad_id, "componente_id" => $componente->id])->first();
                    $componenteUnidad->min = $unidad['min'];
                    $componenteUnidad->max = $unidad['max'];
                    $componenteUnidad->save();
                    unset($unidadesComponente[$index]);
                }
            }
            foreach($unidadesComponente as $unidadABorrar){
                $unidadABorrar->delete();
            }

            $componente->update($request->all());
            return $componente;
        }




        return response()->json(['error' => 'Componente no encontrado'], 404);
    }


    public function delete($id){
        $componente = Componente::find($id);
        if(isset($componente)){
            $componente->delete();
            broadcast(new componenteDeleted());
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
        $fullPath = FileHelper::getRealPath($imageRow->Path);

        return response()->json(
            ["id"=> $imageRow->id
            ,"Path" => $fullPath,
            "Nombre" => $imageRow->Nombre,],
        200);
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

        $image->delete();

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
        $fullPath = FileHelper::getRealPath($image->Path);

        return response()->json(['path' => $fullPath], 200);

    }

    public function listarDispositivosSinNodo()
    {
        $dispositivosSinNodo = Componente::doesntHave('nodo')->get();

        $result = array();
        foreach($dispositivosSinNodo as $componente){
            $tipoComponente = $componente->tipoComponente;
            $pathImage =  FileHelper::getRealPath($tipoComponente->Imagen);
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
        return ($result);
    }

    public function marcaLast24Hours($id){

        $componente = Componente::find($id);
        if(!isset($componente)){
            return response()->json(['message' => "Componente no existe"], 404);
        }
        $result = Registro::select('unidad_id', DB::raw('MAX(Marca) as max'), DB::raw('MIN(Marca) as min'))
        ->where('componente_id', $componente->id)
        ->whereDate('created_at', '>=', now()->subDay())
        ->groupBy('unidad_id')
        ->get();

        return $result;

    }

    public function toggleOn(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'on' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $componente = Componente::find($id);
        if(!isset($componente)){
            return response()->json(['error' => 'Componente no encontrado'], 404);
        }
        $body = $request->all();
        $componente->On = $body['on'];
        $componente->save();
        return response()->json("ok", 200);
    }






}
