<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Mail\ResetPasswordMail;

class ResetPasswordController extends Controller
{
    public function sendEmail(Request $req){
        $email = $req->email;

        if(!$this->validateEmail($email)){
            return  response()
                    ->json(['error' => 'Email incorrecto'], 418);
        }
        
       $token = $this->crearToken($email);

        Mail::to($req->email)->send(new ResetPasswordMail($token));
        return  response()
                ->json(['data' => 'Email mandado correctamente.'], 200);
    }

    public function validateEmail($email){
        return !!User::where('email', $email)->first();
        //ps Al negarlo la primera vez lo haces booleano, pero invertido.
        //ps Al negarlo por segunda vez lo mantienes booleano, pero correcto.
    }

    public function crearToken($email){
        $oldToken = DB::table('password_resets')
                    ->where('email', $email)->first();

        $token = ($oldToken->token ?? Str::random(60));
        //el  = ($oldToken = $oldToken : Str::random(60)), pero más abreviado.
        
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        return $token;
    }

    public function resetPassword(Request $req){
        return  $this->checkeaContra($req) ?
                $this->cambiarContra($req) : 
                $this->tokenNoEncontrado();
    }

    private function checkeaContra($req){
        return DB::table('password_resets')
                ->where([   'email' => $req->email,
                            'token' => $req->resetToken]);
    }

    private function cambiarContra($req){
        $user = User::whereEmail($req->email)->first();

        DB::table('password_resets')
                ->whereEmail($req->email)->delete();

        $user->update(['password' => Hash::make($req->get('password'))]);
        return response()->json(['data' => 'Contraseña cambiada con éxito.'], 201);
    }

    private function tokenNoEncontrado(){
        return response()->json(['error' => 'No se encontró el token.'], 418);
    }
}
