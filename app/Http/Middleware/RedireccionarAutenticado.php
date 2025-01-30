<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedireccionarAutenticado
{
  public function handle(Request $request, Closure $next)
  {
    $ruta = $request->route()->getName();

    // // Verificar si la cookie 'session_user_token' existe
    // if ($ruta == 'loginAdministrativo' && $request->hasCookie('session_user_token')) {
    //   // Redirigir a la ruta de 'home'
    //   return redirect()->route('homeAdministrativo');
    // }

    // Continuar con la solicitud si la cookie no existe
    return $next($request);
  }
}
