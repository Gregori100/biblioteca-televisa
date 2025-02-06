<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Facades\Redis;
use App\Constantes\CodigoRes;
use App\Constantes\PermisoConst;
use App\Constantes\PermisoEnVistaConst;
use App\Constantes\RedisConst;
use App\Utilerias\ApiResponse;
use App\Utilerias\TextoUtils;
use App\Exceptions\ValidacionException;
use App\Services\PermisoService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class ValidarSesion
{
  /**
   * Middleware para validar sesion
   */
  public function handle($request, Closure $next)
  {
    // Obtener el token de la cookie o encabezado
    $token = $request->cookie('session_user_token') ?? $request->header('Authorization');

    if (!$token) {
      Log::error('El token no se encontro en la cookie ' . json_encode($request));

      if ($request->expectsJson()) {
        return response(ApiResponse::build(CodigoRes::ERROR, "Sesión no válida o expirada"));
      }

      return redirect()->route('loginAdministrativo');
    }

    // Verificar si el token existe en Redis
    $usuario = Redis::get($token);

    if (!$usuario) {
      Cookie::queue(Cookie::forget('session_user_token'));
      Log::error('El token no se encontro en redis: ' . $token);

      if ($request->expectsJson()) {
        return response(ApiResponse::build(CodigoRes::ERROR, "Sesión no válida o expirada"));
      }

      return redirect()->route('loginAdministrativo');
    }

    // Refrescar el ttl del token
    Redis::expire($token, env('SESSION_LIFETIME'));

    // Convertir el usuario de JSON a objeto
    $usuarioObj = json_decode($usuario);

    // Agregar el usuarioId a los parámetros de la solicitud
    $request->merge(['usuarioId'        => $usuarioObj->usuario_id]);
    $request->merge(['usuarioSolicitud' => $usuarioObj->usuario]);
    $request->merge(['tokenRedis'       => $token]);

    // Se obtiene permiso petición
    $routeName = $request->route()->getName();
    $permisoPeticion = PermisoConst::LISTADO_PERMISOS[$routeName] ?? null;
    $permisosUsuario = json_decode($usuarioObj->permisos);

    // Validar si el usuario tiene el permiso necesario
    if ($permisoPeticion && !in_array($permisoPeticion, $permisosUsuario)) {
      if ($request->expectsJson()) {
        return response(ApiResponse::build(CodigoRes::ERROR, "No se tiene los permisos necesarios para realizar esta acción"));
      }

      return redirect()->back()->with('error', "No se tiene los permisos necesarios para entrar a la vista");
    }

    // Se obtienen los permisos de usuario en caso de que sea alguna vista
    $permisosVista = PermisoService::obtenerPermisosVista($permisosUsuario, $routeName);

    // Se comparte usuario a todas las vistas blade
    $usuarioObj->permisosArray = json_decode($usuarioObj->permisos);
    View::share('usuarioLogueado', $usuarioObj);
    View::share('permisosVista', $permisosVista);

    // Continuar la solicitud
    $response = $next($request);

    //Adjuntar la nueva cookie
    return $response->cookie('session_user_token', $token, 60 * 4);
  }
}
