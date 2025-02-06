<?php

namespace App\Http\Controllers;

use App\Constantes\CodigoRes;
use App\Constantes\SucursalConst;
use App\Constantes\UsuarioConst;
use App\Coordinators\UsuarioCoordinator;
use App\Exceptions\ExceptionHandler;
use App\Exceptions\ValidacionException;
use App\Services\BO\UsuarioBO;
use App\Services\SucursalService;
use App\Services\UsuarioService;
use App\Utilerias\ApiResponse;
use App\Utilerias\HashUtils;
use App\Utilerias\TextoUtils;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use stdClass;

class UsuarioController extends Controller
{
  /********************************************************************/
  /******************************* API ********************************/
  /********************************************************************/
  /**
   * Listar usuarios
   * @param Request $request
   * @return void
   */
  public function listar(Request $request)
  {
    try {
      $datos = $request->all();

      $columnas = $datos['columnas'] ?? [];
      $filtros  = $datos['filtros'] ?? [];
      $limit    = $datos['limit'] ?? [];
      $offset   = $datos['offset'] ?? [];
      $order    = $datos['order'] ?? [];

      $usuarios = UsuarioService::listar($columnas, $filtros, $limit, $offset, $order);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Listado de usuarios obtenido correctamente.", $usuarios)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioController::listar()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Listar perfiles de usuarios
   * @param Request $request
   * @return void
   */
  public function listarPerfiles(Request $request)
  {
    try {
      $datos = $request->all();

      $columnas = $datos['columnas'] ?? [];
      $filtros  = $datos['filtros'] ?? [];
      $limit    = $datos['limit'] ?? [];
      $offset   = $datos['offset'] ?? [];
      $order    = $datos['order'] ?? [];

      $perfiles = UsuarioService::listarPerfiles($columnas, $filtros, $limit, $offset, $order);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Listado de perfiles de usuarios obtenido correctamente.", $perfiles)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioController::listar()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Método que agrega un usuario
   * @param Request $request
   * @return response
   */
  public function agregar(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        'usuario'        => 'required',
        'nombreCompleto' => 'required',
        'email'          => 'required',
        'password'       => 'required',
        'perfilId'       => 'required',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      $usuarioId = UsuarioCoordinator::agregar($datos);

      $respuesta = new stdClass();
      $respuesta->usuarioId = $usuarioId;
      $respuesta->hashId    = HashUtils::getHash($usuarioId);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Usuario agregado correctamente.", $respuesta)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioController::agregar()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Método que edita un usuario
   * @param Request $request
   * @return response
   */
  public function editar(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        'usuarioEditadoId' => 'required',
        'usuario'          => 'required',
        'nombreCompleto'   => 'required',
        'email'            => 'required',
        'perfilId'         => 'required',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      UsuarioCoordinator::editar($datos);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Usuario editado correctamente.", true)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioController::editar()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Método que edita un usuario
   * @param Request $request
   * @return response
   */
  public function editarPassword(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        'usuarioEditadoId' => 'required',
        'password'         => 'required',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      UsuarioService::editarPassword($datos);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Usuario editado correctamente.", true)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioController::editarPassword()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Método que edita un usuario
   * @param Request $request
   * @return response
   */
  public function eliminar(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        'usuarioEditadoId' => 'required',
        'usuario'          => 'required',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      UsuarioService::eliminar($datos);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Usuario eliminado correctamente.", true)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioController::editarPassword()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Método que agrega un nuevo perfil de acceso
   * @param Request $request
   * @return response
   */
  public function agregarPerfil(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        'clave'       => 'required',
        'titulo'      => 'required',
        'descripcion' => 'required',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      $perfilId = UsuarioService::agregarPerfil($datos);

      $respuesta = new stdClass();
      $respuesta->perfilId = $perfilId;
      $respuesta->hashId   = HashUtils::getHash($perfilId);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Perfil de acceso agregado correctamente.", $respuesta)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioController::agregarPerfil()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Método que elimina un perfil de acceso
   * @param Request $request
   * @return response
   */
  public function eliminarPerfil(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        'perfilId' => 'required',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      UsuarioCoordinator::eliminarPerfil($datos);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Perfil de acceso agregado correctamente.", true)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioController::eliminarPerfil()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Método que edita relacion de sucursales con usarios
   * @param Request $request
   * @return response
   */
  public function editarRelacionSucursales(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        'usuarioEditadoId' => 'required',
        'sucursales'       => 'required',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      UsuarioCoordinator::editarRelacionSucursales($datos);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Sucursales editadas correctamente.", true)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioController::editarRelacionSucursales()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Método que edita un perfil de acceso
   * @param Request $request
   * @return response
   */
  public function editarPerfil(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        'perfilId' => 'required',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      UsuarioCoordinator::editarPerfil($datos);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Perfil de acceso editado correctamente.", true)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioController::editarPerfil()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Método que edita un perfil de acceso
   * @param Request $request
   * @return response
   */
  public function editarPermisosPerfil(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        'perfilId'        => 'required',
        'permisosSeccion' => 'required',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      UsuarioCoordinator::editarPermisosPerfil($datos);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Permisos de perfil de acceso editados correctamente.", true)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioController::editarPermisosPerfil()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /********************************************************************/
  /******************************* WEB ********************************/
  /********************************************************************/
  /**
   * Controller que pre carga información necesaria para
   * el gestor de usuarios
   * @param Request $request
   * @return void
   */
  public function gestor(Request $request)
  {
    try {
      $datos = $request->all();

      $columnas = $datos['columnas'] ?? [];
      $limit    = $datos['limit'] ?? [];
      $offset   = $datos['offset'] ?? [];
      $order    = $datos['order'] ?? [];


      $filtros = [
        "busqueda" => $datos["busqueda"] ?? "",
      ];

      $usuarios = UsuarioService::listar($columnas, $filtros, $limit, $offset, $order);

      $view = view('usuarios.UsuarioGestor', compact('usuarios', 'filtros'));

      return ApiResponse::armarMensajeFlashVista($datos, $view, "usuarios");
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioController::gestor()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Controller que pre carga información necesaria para
   * el detalle de usuario
   * @param Request $request
   * @return void
   */
  public function detalle(Request $request, $id, $hashId)
  {
    try {
      $datos = $request->all();
      HashUtils::validarHash($id, $hashId);

      $usuarioObj             = UsuarioService::obtenerUsuario($id, false)->obtenerObj();
      $sucursales             = SucursalService::listar("", [
        "status" => SucursalConst::SUCURSAL_STATUS_ACTIVO
      ], null, null, ["registro_fecha_desc"]);
      $sucursalesRelacionadas = UsuarioService::listarRelUsuariosSucursales("", [
        "usuarioId" => $id,
        "status"    => [UsuarioConst::REL_SUCURSAL_USUARIO_ACTIVO]
      ]);

      $idsRelacionSucursales = array_map(fn($sucursal) => $sucursal->sucursal_id, $sucursalesRelacionadas);

      foreach ($sucursales as $sucursal) {
        $sucursal->relacionSucursal = in_array($sucursal->sucursal_id, $idsRelacionSucursales);
      }

      $view = view('usuarios.UsuarioDetalle', compact('usuarioObj', 'sucursales', 'sucursalesRelacionadas', 'hashId'));

      return ApiResponse::armarMensajeFlashVista($datos, $view, "usuarios");
    } catch (Exception $e) {
      $codigo = ExceptionHandler::manejarException($e, 'Ocurrio un error al obtener el usuario');
      return redirect()->back()->with('error', $codigo);
    }
  }

  /**
   * Controller que pre carga información necesaria para
   * el gestor de perfiles
   * @param Request $request
   * @return void
   */
  public function perfilesGestor(Request $request)
  {
    try {
      $datos = $request->all();

      $columnas = $datos['columnas'] ?? [];
      $limit    = $datos['limit'] ?? [];
      $offset   = $datos['offset'] ?? [];
      $order    = $datos['order'] ?? [];

      $filtros = [
        "busqueda" => $datos["busqueda"] ?? "",
      ];

      $perfiles = UsuarioService::listarPerfiles($columnas, $filtros, $limit, $offset, $order);

      $view = view('usuarios.PerfilGestor', compact('perfiles', 'filtros'));

      return ApiResponse::armarMensajeFlashVista($datos, $view, "perfiles");
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioController::gestor()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Controller que pre carga información necesaria para
   * el detalle de un perfil de acceso
   * @param Request $request
   * @return void
   */
  public function detallePerfil(Request $request, $id, $hashId)
  {
    try {
      $datos = $request->all();
      HashUtils::validarHash($id, $hashId);

      $response = UsuarioCoordinator::obtenerPerfilUsuario($id);
      $perfilObj         = $response["perfilObj"];
      $seccionesPermisos = $response["seccionesPermisos"];

      $view = view('usuarios.PerfilDetalle', compact(
        'perfilObj',
        'hashId',
        'seccionesPermisos'
      ));

      return ApiResponse::armarMensajeFlashVista($datos, $view, "perfiles");
    } catch (Exception $e) {
      $codigo = ExceptionHandler::manejarException($e, 'Ocurrio un error al obtener el detalle de perfil de acceso');
      return redirect()->back()->with('error', $codigo);
    }
  }

  /**
   * Controller que pre carga información necesaria para
   * el la vista de editar permisos
   * @param Request $request
   * @return void
   */
  public function editarPermisos(Request $request, $id, $hashId)
  {
    try {
      $datos = $request->all();
      HashUtils::validarHash($id, $hashId);

      $response = UsuarioCoordinator::obtenerPerfilUsuario($id);
      $perfilObj         = $response["perfilObj"];
      $seccionesPermisos = $response["seccionesPermisos"];

      $view = view('usuarios.PerfilEditarPermisos', compact(
        'perfilObj',
        'hashId',
        'seccionesPermisos'
      ));

      return ApiResponse::armarMensajeFlashVista($datos, $view, "perfiles");
    } catch (Exception $e) {
      $codigo = ExceptionHandler::manejarException($e, 'Ocurrio un error al obtener el detalle de perfil de acceso');
      return redirect()->back()->with('error', $codigo);
    }
  }
}
