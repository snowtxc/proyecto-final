<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parte;
use App\Models\Componente;
use App\Models\ParteNotas;

use Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\JwtMiddleware;
use App\Helpers\FileHelper;



class ParteController extends Controller
{
    public function __construct()
    {
        $this->middleware(JwtMiddleware::class);
    }

    public function list(Request $request,$componenteId){
       $componente  = Componente::find($componenteId);
       if(!isset($componente)){
              return response()->json(['error' => 'Componente no encontrado'],404);
       }
       return Parte::where("componente_id",$componenteId)->orderBy('created_at','desc')->get();
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            "componente_id"  => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $body =  $request->all();
        $componente =  Componente::find($body['componente_id']);
        if(!isset($componente)){
            return response()->json(['error' => 'Componente no encontrado'], 404);
        }

        $parte = Parte::create($request->all());
        return $parte;
    }


    public function update(Request $request, $componenteId, $id){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            "componente_id"  => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $parte = Parte::find($id);
        if(isset($parte)){
            $parte->update($request->all());
            return $parte;
        }
        return response()->json(['error' => 'Parte no encontrada'], 404);
    }


    public function delete($componenteId, $parteId){
        $componente = Componente::find($componenteId);
        if(!isset($componente)){
            return response()->json(['error' => 'Componente no encontrado'], 404);
        }
        $parte = $componente->partes()->find($parteId);
        if(isset($parte)){
            $parte->delete();
            return response()->json($parte, 200);
        }
        return response()->json(['error' => 'Parte no encontrada'], 404);
    }

    public function getById($componenteId, $parteId){
        $componente = Componente::find($componenteId);
        if(!isset($componente)){
            return response()->json(['error' => 'Componente no encontrado'], 404);
        }
        $parte = $componente->partes()->find($parteId);

        if(isset($parte)){
            return response()->json($parte, 404);
        }
        return response()->json(['error' => 'Parte no encontrada'], 404);
    }


    public function listNotas(Request $request ,$componenteId, $parteId){
            $componente = Componente::find($componenteId);
            if(!isset($componente)){
                return response()->json(['error' => 'Componente no encontrado'], 404);
            }
            $parte = $componente->partes()->find($parteId);

            if(!isset($parte)){
                return response()->json($parte, 404);
            }

            $page = ($request->query('page') != null && $request->query('page') >= 1) ? $request->query('page') : 1;


            $maxRows = $request->query('maxRows') != null ? $request->query('maxRows') : 5;
            $offset = $page == 1 ? 0 : (($page - 1) * $maxRows);

            $notas = ParteNotas::where("parte_id",$parteId)->orderBy('created_at','desc')->offset($offset)->limit($maxRows)->get();
            $countRows = ParteNotas::where("parte_id",$parteId)->orderBy('created_at','desc')->count();
            $result = array();

            foreach ($notas as $nota) {
                $user =[
                    "id" => $nota->user->id,
                    "name" => $nota->user->name,
                    "email" => $nota->user->email,
                    "profileImage" =>   isset($nota->user->profileImage) ? FileHelper::getRealPath($nota->user->profileImage) : null,
                    "created_at" => $nota->user->created_at,
                    "updated_at" => $nota->user->updated_at];

                $data= array_merge($nota->toArray(), ["user" => $user]);
                array_push($result, $data);
            }
            return array("data" => $result, "countRows" => $countRows);
        }


    public function addNota(Request $request ,$componenteId, $parteId){
        $userInfo  = Auth::user();
        $userID = $userInfo->id;

        $validator = Validator::make($request->all(), [
            'Descripcion' => 'required|min:10',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $componente = Componente::find($componenteId);
        if(!isset($componente)){
            return response()->json(['error' => 'Componente no encontrado'], 404);
        }
        $parte = $componente->partes()->find($parteId);
        if(!isset($parte)){
            return response()->json(['error' => 'Parte no encontrada'], 404);
        }
        $body =  $request->all();
        $notaCreated =$parte->notas()->create([
            'Descripcion' => $body['Descripcion'],
            'user_id' => $userID,
        ]);

        $user = [
            "id" => $userInfo->id,
            "name" => $userInfo->name,
            "email" => $userInfo->email,
            "profileImage" =>   isset($userInfo->profileImage) ?  FileHelper::getRealPath($userInfo->profileImage) : null,
            "created_at" => $userInfo->created_at,
            "updated_at" => $userInfo->updated_at
        ];
        $result = array_merge($notaCreated->toArray(), ["user" => $user]);
        return response()->json( $result, 200);
    }
}
