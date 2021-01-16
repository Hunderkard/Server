<?php

use Illuminate\Support\Facades\Route;
//ag Ahora hay que importar los controladores.
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


//ag Para poder usarlos de la nueva manera.
Route::post('login',  [UserController::class, 'authenticate']);
Route::post('register', [UserController::class, 'register']);
Route::post('sendPasswordReset', [ResetPasswordController::class, 'sendEmail']);

Route::group(['middleware' => ['jwt.verify']], function() {
   //fu AQUÍ LAS RUTAS PROTEGIDAS
});