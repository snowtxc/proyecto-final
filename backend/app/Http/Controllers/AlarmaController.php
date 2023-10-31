<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alarma;
use App\Helpers\FileHelper;
use Carbon\Carbon;
use App\Models\Proceso;
use App\Models\AlarmaUser;
use App\Models\Componente;
use App\Models\User;
use Illuminate\Support\Facades\Mail;


class AlarmaController extends Controller
{
    public function list(Request $request){

        $page = ($request->query('page') != null && $request->query('page') >= 1) ? $request->query('page') : 1;
        $maxRows = $request->query('maxRows') != null ? $request->query('maxRows') : 10;
        $offset = $page == 1 ? 0 : (($page - 1) * $maxRows);

        $procesoId  = $request->query('proceso_id') != null ? $request->query('proceso_id') : null;
        $componenteId  = $request->query('componente_id') != null ? $request->query('componente_id') : null;
        $fechaInicio = $request->query('fechaInicio') != null ? $request->query('fechaInicio') : null;
        $fechaFin =  $request->query('fechaFin') != null ? $request->query('fechaFin') : null;

        $query  =  Alarma::when(isset($procesoId), function ($query) use ($procesoId) {
            $query->where('proceso_id', '=', $procesoId);
        })->when(isset($componenteId), function ($query) use ($componenteId) {
            $query->where('componente_id', '=', $componenteId);
        })->when(isset($fechaInicio), function ($query) use ($fechaInicio) {
            $start = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
            $query->whereDate('created_at', ">=" , $start);
        })->when(isset($fechaFin), function ($query) use ($fechaFin) {
            $end  =  Carbon::createFromFormat('d-m-Y', $fechaFin)->format('Y-m-d');
            $query->whereDate('created_at', "<=" , $end);
        })->orderBy('created_at','desc');

        $queryPaginated  = $query->skip($offset)->take($maxRows);

        $countRows =  $query->count();
        $alarmas = $queryPaginated->get();

        $result = array();
        foreach($alarmas as $alarma){
              $componente = $alarma->componente;
              $proceso =  $alarma->proceso;
              $tipoComponente = $componente->tipoComponente;
              $imagen = $tipoComponente->Imagen;
              $urlPath = FileHelper::getRealPath($imagen);

              array_push($result,
                    [
                        "id" => $alarma->id,
                        "componenteNombre" => $componente->Nombre,
                        "tipoComponente" => $tipoComponente->Nombre,
                        "procesoNombre" => $proceso->Nombre,
                        "fechaHora"  => $alarma->created_at,
                        "tipoComponenteImagen" => $urlPath
                    ]
                );


        }

        return array("data" => $result, "countRows" => $countRows);
    }

    public function getUsers($id){
        $alarma =  Alarma::find($id);
        if(!isset($alarma)){
            return response()->json(['error' => 'Alarma no encontrada'], 404);
        }

        $users = $alarma->users;
        $data = [];
        foreach($users as $user){
            $userFormatted = [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "profileImage" =>  isset($user->profileImage) ? FileHelper::getRealPath($user->profileImage) : null,
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at,
            ];
            array_push($data, $userFormatted);
        }
        return response()->json($data, 200);
    }

    public function create(Request $request) {
        $componenteId = $request->input('componente_id');
        $procesoId = $request->input('proceso_id');

        $componente = Componente::find($componenteId);
        if(!isset($componente)){
            return response()->json(['error' => 'Dispositivo no encontrado'], 404);
        }
        $proceso =  Proceso::find($procesoId);
        if(!isset($proceso)){
            return response()->json(['error' => 'Proceso no encontrado'], 404);
        }

        $alarma = new Alarma;
        $alarma->componente_id = $componenteId;
        $alarma->proceso_id = $procesoId;
        $alarma->save();
        
        $usuarios = $proceso->users;
    
        foreach ($usuarios as $usuario) {
            $data = [
                'name' => $usuario->name,
                'dispositivo' => $componente->Nombre,
                'proceso' => $proceso->Nombre
            ];
    
            Mail::send('emails.alarma', $data, function ($message) use ($usuario) {
                $message->to($usuario->email)->subject('Nueva Alarma');
            });
        }
    
        foreach ($usuarios as $usuario) {
            $alarmaUser = new AlarmaUser;
            $alarmaUser->alarma_id = $alarma->id;
            $alarmaUser->user_id = $usuario->id;
            $alarmaUser->save();
        }
    
        return response()->json(['message' => 'Alarma creada y notificaciones enviadas.']);
    }
    
    public function getByUser(Request $request){
        $id = $request->query('id');
        $page = $request->query('page');
        $user = User::findOrFail($id);
        $rows = 10; 
        $alarmas = $user->alarmas()->paginate($rows, ['*'], 'page', $page);

        $result = array();
        foreach($alarmas as $alarma){
              $componente = $alarma->componente;
              $proceso =  $alarma->proceso;
              $tipoComponente = $componente->tipoComponente;
              $imagen = $tipoComponente->Imagen;
              $urlPath = FileHelper::getRealPath($imagen);

              array_push($result,
                    [
                        "id" => $alarma->id,
                        "componenteNombre" => $componente->Nombre,
                        "tipoComponente" => $tipoComponente->Nombre,
                        "procesoNombre" => $proceso->Nombre,
                        "fechaHora"  => $alarma->created_at,
                        "tipoComponenteImagen" => $urlPath
                    ]
                );
        }

        $response = [
            'data' => $result,
            'current_page' => $alarmas->currentPage(), 
            'last_page' => $alarmas->lastPage()
        ];
        
        return response()->json($response, 200);
    }

}
