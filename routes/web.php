<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
  return redirect('/login');
});

Route::group(['middleware' => ["no.cache", "redireccionar.autenticado"]], function () {
  Route::get('/login', function () {
    return view('login.Login');
  })->name("loginAdministrativo");
});

Route::post('/auth/login', [AuthController::class, 'authenticate'])->name("auth.login");
