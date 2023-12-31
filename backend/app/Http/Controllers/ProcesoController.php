<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\User;

use Validator;
use App\Http\Middleware\JwtMiddleware;
use App\Helpers\FileHelper;
use App\Events\procesoAdded;
use App\Events\procesoDeleted;



class ProcesoController extends Controller
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

        broadcast(new procesoAdded());
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

    public function delete($id)
{
    $process = Proceso::find($id);

    if (!isset($process)) {
        return response()->json(['error' => 'Proceso no encontrado'], 404);
    }

    // Obtener todas las etapas asociadas al proceso
    $etapas = $process->etapas;

    // Iterar sobre las etapas y eliminar nodos y enlaces asociados
    foreach ($etapas as $etapa) {
        $etapa->nodos()->delete();
        $etapa->links()->delete();
    }

    // Eliminar todas las etapas asociadas al proceso
    $process->etapas()->delete();

    // Eliminar el proceso en sí
    $process->delete();

    broadcast(new procesoDeleted());

    return response()->json(['success' => 'Proceso y etapas relacionadas borrados'], 200);
}



    public function getEtapasByProcess($id){
        $process =  Proceso::find($id);
        if(!isset($process)){
            return response()->json(['error' => 'Proceso no encontrado'], 404);
        }
        return response()->json($process->etapas, 200);
    }


    //agregamos un usuario al proceso.
    public function addUsersToProcess(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'usersIdArr.*' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $body = $request->all();
        $usersIdArr = $body['usersIdArr'];

        $process =  Proceso::find($id);
        if(!isset($process)){
            return response()->json(['error' => 'Proceso no encontrado'], 404);
        }

        if(User::whereIn('id', $usersIdArr)->doesntExist()){
            return response()->json(['error' => 'Hay un id que no corresponde a un usuario'], 400);
        }

        $process->users()->attach($usersIdArr);

        return response()->json(["message"=> "Usuarios han sido agregados correctamente al proceso"], 200);

    }

    public function removeUserFromProcess(Request $request, $id, $userId){

        $process =  Proceso::find($id);
        if(!isset($process)){
            return response()->json(['error' => 'Proceso no encontrado'], 404);
        }

        $user = User::find($userId);
        if(!isset($user)){
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        $userAlReadyExist =  $process->users()->find($userId) != null ? true: false ;
        if(!$userAlReadyExist){
            return response()->json(['error' => 'Usuario no pertenece al proceso'], 400);
        }
        $process->users()->detach($userId);

        return response()->json(["message"=> "Usuario ha sido removido correctamente del proceso"], 200);
    }

    public function getUsersByProcess(Request $request, $id){
        $process =  Proceso::find($id);
        if(!isset($process)){
            return response()->json(['error' => 'Proceso no encontrado'], 404);
        }

        $users = $process->users;
        $data = [];
        foreach($users as $user){
            $userFormatted = [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "rol" => $user->rol,
                "profileImage" =>  isset($user->profileImage) ? FileHelper::getRealPath($user->profileImage) : null,
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at,
            ];
            array_push($data, $userFormatted);
        }
        return response()->json($data, 200);
    }

    public function getUsersNotInProcess(Request $request, $id){
        $proceso = Proceso::find($id);
        if(!isset($proceso)){
            return response()->json(['error' => 'Proceso no encontrado'], 404);
        }

        $usuarios  =User::whereDoesntHave('procesos', function ($query) use ($id) {
            $query->where('proceso_id', $id);
        })->get();

        $data = array();
        foreach($usuarios as $usuario){
            $userFormatted = [
                "id" => $usuario->id,
                "name" => $usuario->name,
                "email" => $usuario->email,
                "rol" => $usuario->rol,
                "profileImage" =>  isset($usuario->profileImage) ? FileHelper::getRealPath($usuario->profileImage) : null,
                "created_at" => $usuario->created_at,
                "updated_at" => $usuario->updated_at,
            ];
            array_push($data, $userFormatted);
        }

       return response()->json($data, 200);

    }

    public function getByUser(Request $request){
        $id = $request->query('id');
        $page = $request->query('page');
        $user = User::findOrFail($id);
        $rows = 10; 
        $procesos = $user->procesos()->paginate($rows, ['*'], 'page', $page);
    
        return response()->json($procesos, 200);
    }
    public function getProcesosByUser($userId)
    {
        $user = User::find($userId);
        if (!isset($user)) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $procesos = $user->procesos;

        $data = [];
        foreach ($procesos as $proceso) {
            $procesoFormatted = [
                "id" => $proceso->id,
                "Nombre" => $proceso->Nombre,
                "Descripcion" => $proceso->Descripcion,
                "created_at" => $proceso->created_at,
                "updated_at" => $proceso->updated_at,
            ];
            array_push($data, $procesoFormatted);
        }

        return response()->json($data, 200);
    }


}
