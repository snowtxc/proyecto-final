<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoComponente;
use App\Helpers\FileHelper;

use Validator;
class TipoComponenteController extends Controller
{
    //
    public function list()
    {
        $tipos_componentes = TipoComponente::all();
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
        return $tipoComponente;
    }
}
