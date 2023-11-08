<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoComponente;
use App\Helpers\FileHelper;
use App\Events\tipoComponenteAdded;
use App\Events\tipoComponenteDeleted;


use Validator;
class TipoComponenteController extends Controller
{
    //
    public function list(Request $request)
    {
        $search = $request->query('search') != null ? $request->query('search') : null;

        $tipos_componentes = TipoComponente::when(isset($search), function ($query) use ($search) {
            $query->where('Nombre', 'like', '%' . $search . '%');
        })->get();

        $result = [];
        foreach($tipos_componentes as $tipo_componente){
            $pathImage =  FileHelper::getRealPath($tipo_componente->Imagen);
            array_push($result,
                [
                    "Nombre" => $tipo_componente->Nombre,
                    "Imagen" => $pathImage,
                    "id" => $tipo_componente->id
                ]
            );
        }
        return $result;
    }



    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            'Imagen' => 'required|file'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $body = $request->all();
        $file = $body['Imagen'];
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

        $pathUploaded = FileHelper::uploadFile($file, 'public/tipos-componentes',$fileName);
        $tipoComponente = TipoComponente::create([
            'Nombre' => $body['Nombre'],
            'Imagen' => $pathUploaded
        ]);

        broadcast(new tipoComponenteAdded());
        return [
            "id" => $tipoComponente->id,
            'Nombre' => $tipoComponente->Nombre,
            "Imagen" => FileHelper::getRealPath($tipoComponente->Imagen),
        ];
    }

    public function delete($id){
        $tipo_componente = TipoComponente::find($id);
        if(isset($tipo_componente)){
            $tipo_componente->delete();
            broadcast(new tipoComponenteDeleted());
            return response()->json($tipo_componente, 200);
        }


        return response()->json(['error' => 'Componente no encontrado'], 404);
    }

    public function edit(Request $request ,$id){

        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $tipoComponente =  TipoComponente::find($id);
        if(!isset($tipoComponente)){
            return response()->json(["message" =>  "Tipo de componente no existe"],404);
        }
        $body = $request->all();
        if(isset($body['Imagen'])){
            FileHelper::deleteFile($tipoComponente->Imagen);  //eliminamos del storage la ultima imagen para guardar y asignar una nueva;
            $file = $body['Imagen'];
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $pathUploaded = FileHelper::uploadFile($file, 'public/tipos-componentes',$fileName);
            $tipoComponente->Imagen = $pathUploaded;
        }
        $tipoComponente->Nombre = $body['Nombre'];
        $tipoComponente->update();

        return [
            "id" => $tipoComponente->id,
            'Nombre' => $tipoComponente->Nombre,
            "Imagen" => FileHelper::getRealPath($tipoComponente->Imagen),
        ];


    }
}
