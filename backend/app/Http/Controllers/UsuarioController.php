<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Helpers\FileHelper;


class UsuarioController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(JwtMiddleware::class, ['except' => ['login', 'setPassword']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            $userInfo = Auth::user();

            $user =  array(
                "id" => $userInfo->id,
                "name" => $userInfo->name,
                "email" => $userInfo->email,
                "profileImage" =>   isset($userInfo->profileImage) ? FileHelper::getRealPath($userInfo->profileImage) : null,
                "created_at" => $userInfo->created_at,
                "updated_at" => $userInfo->updated_at,
            );

            return compact('user', 'token');
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function isLogged()
    {
        return response()->json(['message' => 'Is authenticated']);
    }




    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function getUsers()
    {
        $users = User::all();

        return response()->json(['users' => $users], 200);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }

    public function index()
    {
        $users = User::all();
        $result = array();
        foreach($users as $user){
            $result[] = array(
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "profileImage" =>   isset($user->profileImage) ?  FileHelper::getRealPath($user->profileImage) : null,
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at,
            );
        }
        return $result;
    }

    public function nuevo(Request $request)
    {
        $password = Str::random(8);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->rol = $request->rol;
        $user->password = bcrypt($password);
        $user->save();

        // Generar un token de restablecimiento de contraseña
        $token = Str::random(64);

        // Almacenar el token en la tabla password_resets
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Enviar el correo electrónico al usuario
        Mail::send('emails.reset_password', [ 'token' => $token, 'name' => $request->name ], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Restablecimiento de contraseña');
        });

        return $user;
    }

    public function buscar($id)
    {
        return User::findOrFail($id);
    }

    public function editar(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->rol = $request->rol;

        if(isset($request->password) && $request->password != ''){
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return $user;
    }

    public function eliminar($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado']);
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::unique('users'),
            ],
        ]);

        return response()->json(['message' => 'El correo electrónico está disponible']);
    }

    public function setPassword(Request $request)
    {
        if(!isset($request->token)){
            return response()->json(['message' => 'Falta token'], 400);
        }

        $reset = DB::table('password_resets')->where('token', $request->token)->first();

        if (!$reset) {
            return response()->json(['message' => 'Token inválido'], 400);
        }

        $user = User::where('email', $reset->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_resets')->where('email', $user->email)->delete();

        return response()->json(['message' => 'Contraseña actualizada con éxito']);
    }

    function changeMeProfileImage(Request $request) {
        $validator = Validator::make($request->all(), [
            'profileImage' => 'required|file'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user  = Auth::user();
        $userID = $user->id;
        $body = $request->all();
        if(isset($user->profileImage)){
            FileHelper::deleteFile($user->profileImage);
        }
        $image = FileHelper::uploadFile($body['profileImage'], 'public/profiles',  uniqid().'.jpg');
        $user->profileImage = $image;
        $user->save();

        $path =   FileHelper::getRealPath($image);


        return response()->json($path,200);
    }


}
