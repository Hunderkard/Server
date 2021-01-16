<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

//el Contraseñas, validadores y los JWT.
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;    //ps Para esto le agregamos el facade/aliases...
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
//hi URL /login.
public function authenticate(Request $request) {
    //ti Sólo tomamos el email y el password.
    $credentials = $request->only('email', 'password');
    
    try {
        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    } catch (JWTException $e) {
        return response()->json(['error' => 'could_not_create_token'], 500);
    }
    return response()->json(compact('token'));
}

public function getAuthenticatedUser(){
    try {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
        }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
}
//hi URL /register.
public function register(Request $request) {
    //bi CHECKEACIÓN
    $validator = Validator::make($request->all(), [
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users',
    'password' => 'required|string|min:6',
    ]); //ps Esto lo comprobaré en Angular, pero nunca viene mal otro vistazo.
    //bi CONTESTACIÓN
    if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
    }
    //bi CREACIÓN
    $user = User::create([
        'name' => $request->get('name'),
        'email' => $request->get('email'),
        'password' => Hash::make($request->get('password')),
        'level' => 1
    ]);

    $token = JWTAuth::fromUser($user);
    //bi RETORNACIÓN
    return response()->json(compact('user','token'),201);
}

}
