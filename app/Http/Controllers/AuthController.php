<?php

namespace App\Http\Controllers;

use App\Constantes\CodigoRes;
use App\Constantes\MensajeValidacion;
use App\Coordinators\UsuarioCoordinator;
use App\Exceptions\ExceptionHandler;
use App\Exceptions\ValidacionException;
use App\Services\Actions\UsuarioServiceAction;
use App\Services\Data\UsuarioServiceData;
use App\Utilerias\ApiResponse;
use App\Utilerias\TextoUtils;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
  /**
   * Método para autenticar usuario (login)
   * @param Request $request
   * @return ApiResponse
   * @throws Exception
   */
  public function authenticate(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        'usuario'  => 'required|max:20',
        'password' => 'required|max:128',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      $token = UsuarioCoordinator::autenticarUsuario($datos);

      return redirect()->route('libros.gestor')->cookie('session_user_token', $token, 60 * 4);
    } catch (ValidacionException $e) {
      return redirect()->route('loginAdministrativo')->withInput()
        ->with('error', "Usuario o password requerido");
    } catch (AuthenticationException $e) {
      return redirect()->route('loginAdministrativo')->withInput()
        ->with('error', $e->getMessage());
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "AuthController::authenticate()");
      return redirect()->route('loginAdministrativo')->withInput()
        ->with('error', 'Ocurrió un error durante la operación, no fue posible iniciar sesión');
    }
  }

  /**
   * Método para cerrar sesión
   * @param Request $request
   * @return ApiResponse
   * @throws Exception
   */
  public function cerrarSesion(Request $request)
  {
    try {
      $datos = $request->all();

      UsuarioCoordinator::cerrarSesion($datos);

      return redirect()->route('loginAdministrativo');
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "AuthController::cerrarSesion()");
      $mensajeError = ExceptionHandler::manejarException($e, "Ocurrió un error al cerrar sesión");
      return back()->with('error', $mensajeError);
    }
  }
}
