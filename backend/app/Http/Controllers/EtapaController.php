<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Etapa;
use Validator;
use App\Http\Middleware\JwtMiddleware;

class EtapaController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(JwtMiddleware::class, ['except' => []]);
    }

    public function list($procesoId){
        $etapas = Etapa::where('proceso_id', $procesoId)->get();
    
        return $etapas;
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            "Descripcion" => "required",
            "proceso_id" => "required|exists:procesos,id"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $etapa = Etapa::create($request->all());
        return $etapa;
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            "Descripcion" => "required",
            "proceso_id" => "required|exists:procesos,id"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $etapa = Etapa::find($id);

        if(isset($group)){
            $etapa->update($request->all());
            return $etapa;
        }
        return response()->json(['error' => 'Etapa no encontrada'], 404);
    }


    public function delete($id){
        $etapa = Etapa::find($id);
        if(isset($etapa)){
            $etapa->delete();
            return response()->json(['success' => 'Etapa borrada'], 200);
        }
        return response()->json(['error' => 'Etapa no encontrada'], 404);
    }
}
