<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\UsuarioController;
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

/************************************************/
/************* Rutas administrativo *************/
/************************************************/
Route::group(['middleware' => ["no.cache", "validar.sesion"]], function () {
  Route::post('/logout', [AuthController::class, 'cerrarSesion'])->name('logout');

  Route::prefix('libros')->name('libros.')->group(function () {
    Route::controller(LibroController::class)->group(function () {
      // Web
      Route::get('/', 'gestor')->name('gestor');

      // API
      Route::get('/listar-generos', 'listarGeneros')->name('listarGeneros');
      Route::get('/listar-idiomas', 'listarIdiomas')->name('listarIdiomas');
      Route::get('/listar-autores', 'listarAutores')->name('listarAutores');
      Route::get('/listar-editoriales', 'listarEditoriales')->name('listarEditoriales');
      Route::post('/leer-excel', 'leerExcel')->name('leerExcel');
      Route::post('/agregar', 'agregar')->name('agregar');
      Route::post('/editar', 'editar')->name('editar');
      Route::post('/eliminar', 'eliminar')->name('eliminar');
      Route::post('/ocupar', 'ocupar')->name('ocupar');
    });
  });

  Route::prefix('usuarios')->name('usuarios.')->group(function () {
    Route::controller(UsuarioController::class)->group(function () {
      // Web
      Route::get('/', 'gestor')->name('gestor');
      Route::get('/{id}/{hashId}', 'detalle')->name('detalle');
      Route::get('/perfiles', 'perfilesGestor')->name('perfilesGestor');
      Route::get('/perfiles/{id}/{hashId}', 'detallePerfil')->name('detallePerfil');
      Route::get('/perfiles/editar/{id}/{hashId}', 'editarPermisos')->name('editarPermisos');

      // API
      Route::get('/listar-perfiles', 'listarPerfiles')->name('listarPerfiles');
      Route::post('/agregar', 'agregar')->name('agregar');
      Route::post('/editar', 'editar')->name('editar');
      Route::post('/editar-password', 'editarPassword')->name('editarPassword');
      Route::post('/eliminar', 'eliminar')->name('eliminar');
      Route::post('/agregar-perfil', 'agregarPerfil')->name('agregarPerfil');
      Route::post('/eliminar-perfil', 'eliminarPerfil')->name('eliminarPerfil');
      Route::post('/editar-perfil', 'editarPerfil')->name('editarPerfil');
      Route::post('/editar-permisos-perfil', 'editarPermisosPerfil')->name('editarPermisosPerfil');
    });
  });
});
