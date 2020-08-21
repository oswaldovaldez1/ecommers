<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\UserModel;
use App\Mail\correos;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    private $_roles = array("Admin", "Vendedor", "Comprador");
    public function setRestore($email, Request $request)
    {

        $usuario = UserModel::where('email', $email)->first();
        if ($usuario != null) {
            $usuario->api_token = $this->generateRandomString(30);
            $usuario->update();

            \Config::set('mail.username', 'ventas@lanesa1957.com');
            $datos = new \stdClass();
            $datos->from = \config('mail')['username'];
            $datos->view = 'mails.recuperacion';
            $datos->text = 'mails.recuperacion';
            $datos->fromname = 'La Nesa1957 ventas';
            $datos->archivo = null;
            $datos->filename = "";
            $datos->token = $usuario->api_token;
            $datos->subject = 'Recuperación de contraseña';
            Mail::to($email)->send(new correos($datos));
            return $usuario;
        } else {
            return response()->json(['message' => 'no se encontro en la busqueda'], 404, [], JSON_UNESCAPED_SLASHES);
        }
    }
    public function getRestore($token)
    {
        $usuario = UserModel::where('api_token', $token)->first();

        if ($usuario != null) {
            return $usuario;
        } else {
            return response()->json(['message' => 'no se encontro en la busqueda'], 404, [], JSON_UNESCAPED_SLASHES);
        }
    }
    public function updatePassword($email, Request $request)
    {

        $usuario = UserModel::where('email', $email)->where('api_token', $request->api_token)->first();
        if ($usuario != null) {
            $usuario->api_token = '';
            $usuario->password
                = bcrypt($request->password);
            $usuario->update();
            return response()->json(['message' => 'contraseña actualizada']);
        } else {
            return response()->json(['message' => 'no se encontro en la busqueda'], 404, [], JSON_UNESCAPED_SLASHES);
        }
    }
    public function signup(Request $request)
    {

        $request->validate([
            'firstname' => 'required|string',
            'email' => 'required|string|email|unique:user',
            'password' => 'required|string'
        ]);



        $user = new UserModel();
        $user->firstname = $request->firstname;
        $user->secondname = $request->secondname;
        $user->lastname = $request->lastname;
        $user->phone = $request->phone;
        $user->api_token = '';
        $user->remember_token = '';
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->id_rol = $request->rol + 1;
        $user->tipo = $request->rol;


        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'id' => $user->id,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'name' => $user->firstname . ' ' . $user->secondname,
            'user_type' => $this->_roles[$user->tipo],
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
