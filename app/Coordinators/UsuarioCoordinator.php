<?php

namespace App\Coordinators;

use App\Constantes\RedisConst;
use App\Constantes\SucursalConst;
use App\Constantes\UsuarioConst;
use App\Objects\PerfilObj;
use App\Repositories\Action\UsuarioRepoAction;
use App\Services\BO\RedisBO;
use App\Services\PermisoService;
use App\Services\RedisService;
use App\Services\SucursalService;
use App\Services\UsuarioService;
use App\Utilerias\FechaUtils;
use App\Utilerias\TextoUtils;
use Exception;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use UnexpectedValueException;

class UsuarioCoordinator
{
  /**
   * Coordinator que autentica un usuario
   * @param array $datos
   * @return string
   */
  public static function autenticarUsuario($datos)
  {
    $usuario  = $datos["usuario"];
    $password = $datos["password"];

    // Autenticación en BD
    $usuarioObj           = UsuarioService::autenticarUsuario($usuario, $password);
    $usuarioObj->permisos = json_encode(array_map(function ($permiso) {
      return $permiso->codigo;
    }, PermisoService::listarPorUsuario($usuarioObj->usuario_id)));

    // Guardado de token de login en redis
    $token = RedisService::guardarKeyRedis(
      RedisConst::PREFIJO_AUTH_SESSION_USER,
      [
        "usuarioId" => $usuarioObj->usuario_id
      ],
      true,
      $usuarioObj,
      RedisConst::TTL_DEFAULT
    );

    // Se edita la ultima fecha de acceso
    UsuarioRepoAction::editar(['fecha_ultimo_acceso' => FechaUtils::fechaActual()], $usuarioObj->usuario_id);

    return $token;
  }

  /**
   * Coordinator que cierra una sesión
   * @param array $datos
   * @return string
   */
  public static function cerrarSesion($datos)
  {
    $usuarioId        = $datos["usuarioId"];

    // Eliminación de sesión relacionada
    RedisService::guardarKeyRedis(RedisConst::PREFIJO_AUTH_SESSION_USER, ["usuarioId" => $usuarioId]);

    Cookie::queue(Cookie::forget('session_user_token'));
  }

  /**
   * Coordinator que agrega un usuario
   * @param array $datos
   * @return string $usuarioId
   */
  public static function agregar($datos)
  {
    try {
      return DB::transaction(function () use ($datos) {
        // Se valida el perfil
        UsuarioService::obtenerPerfilUsuario($datos["perfilId"]);

        // Se obtiene sucursal default
        $datos["sucursalId"] = SucursalService::listar("", ["default" => true])[0]->sucursal_id;

        // Se agrega usuario
        $datos["usuarioEditadoId"] = UsuarioService::agregar($datos);

        // Agregar relación con perfil
        UsuarioService::agregarRelacionPerfilUsuario($datos);

        // Agregar relación con sucursal
        UsuarioService::agregarRelacionSucursalUsuario($datos);

        return $datos["usuarioEditadoId"];
      }, 5);
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioCoordinator::agregar()");
      throw new UnexpectedValueException(
        "Problema en servicio agregar usuario. {$e->getMessage()} ",
        300,
        $e
      );
    }
  }

  /**
   * Coordinator que edita un usuario
   * @param array $datos
   * @return void
   */
  public static function editar($datos)
  {
    try {
      DB::transaction(function () use ($datos) {
        // Se valida el usuario
        $usuarioObj = UsuarioService::obtenerUsuario($datos["usuarioEditadoId"]);

        // Si es nuevo perfil, se elimina relación anterior y se crea la nueva
        if ($usuarioObj->getPerfilId() != $datos["perfilId"]) {
          UsuarioService::obtenerPerfilUsuario($datos["perfilId"]);

          // Se elimina la relación actual
          $relacionActual = UsuarioService::listarRelUsuariosPerfiles("", [
            "perfilId"  => $usuarioObj->getPerfilId(),
            "usuarioId" => $datos["usuarioEditadoId"],
            "status"    => [UsuarioConst::REL_PERFIL_USUARIO_ACTIVO],
          ])[0];

          UsuarioService::actualizarStatusRelPefilUsuario(
            $relacionActual->rel_usuario_perfil_id,
            $datos["usuarioId"],
            UsuarioConst::REL_PERFIL_USUARIO_INACTIVO
          );

          // Se verifica si ya existe
          $relacionAnterior = UsuarioService::listarRelUsuariosPerfiles("", [
            "perfilId"  => $datos["perfilId"],
            "usuarioId" => $datos["usuarioEditadoId"],
            "status"    => [UsuarioConst::REL_PERFIL_USUARIO_INACTIVO],
          ]);

          if (!empty($relacionAnterior)) {
            UsuarioService::actualizarStatusRelPefilUsuario(
              $relacionAnterior[0]->rel_usuario_perfil_id,
              $datos["usuarioId"],
              UsuarioConst::REL_PERFIL_USUARIO_ACTIVO
            );
          } else {
            // Agregar relación con perfil
            UsuarioService::agregarRelacionPerfilUsuario($datos);
          }
        }

        // Se edita el usuario
        UsuarioService::editar($datos);

        // Se actualiza objeto de redis en caso de que exista
        UsuarioService::actualizarObjetoUsuarioRedis($datos["usuarioEditadoId"]);
      }, 5);
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioCoordinator::editar()");
      throw new UnexpectedValueException(
        "Problema en servicio editar usuario. {$e->getMessage()} ",
        300,
        $e
      );
    }
  }

  /**
   * Coordinator que elimina un perfil
   * @param array $datos
   * @return void
   */
  public static function eliminarPerfil($datos)
  {
    try {
      DB::transaction(function () use ($datos) {
        // Se valida el perfil
        UsuarioService::obtenerPerfilUsuario($datos["perfilId"]);

        // Se lista registro de usuarios relacionados
        $registroUsuarios = UsuarioService::listar("", [
          "perfilId"                    => $datos["perfilId"],
          "statusRelacionPerfilUsuario" => [UsuarioConst::REL_PERFIL_USUARIO_ACTIVO],
          "status"                      => [UsuarioConst::USUARIO_STATUS_ACTIVO],
        ]);

        if (!empty($registroUsuarios)) {
          throw new Exception("No se puede eliminar el perfil, se encuentra relacionado con un usuario activo.", 300);
        }

        // Agregar relación con perfil
        UsuarioService::eliminarPerfil($datos);
      }, 5);
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioCoordinator::eliminarPerfil()");
      throw new UnexpectedValueException(
        "Problema en servicio eliminar perfil. {$e->getMessage()} ",
        300,
        $e
      );
    }
  }

  /**
   * Coordinator que edita relación con sucursales
   * @param array $datos
   * @return void
   */
  public static function editarRelacionSucursales($datos)
  {
    try {
      return DB::transaction(function () use ($datos) {
        // Se valida usuario
        $usuarioObj = UsuarioService::obtenerUsuario($datos["usuarioEditadoId"]);

        // Se valida sucursales
        $sucursales    = json_decode($datos["sucursales"]);
        $sucursalesIds =  array_map(fn($sucursal) => $sucursal->sucursal_id, $sucursales);

        $registroSucursales = SucursalService::listar("", [
          "sucursalesIds" => $sucursalesIds
        ]);

        if (empty($registroSucursales) || count($registroSucursales) != count($sucursales)) {
          throw new Exception("No se encontró registro de las sucursales.", 300);
        }

        foreach ($registroSucursales as $registroSucursal) {
          if ($registroSucursal->status != SucursalConst::SUCURSAL_STATUS_ACTIVO) {
            throw new Exception("La sucursal {$registroSucursal->titulo} no se encuentra activa.", 300);
          }
        }

        // Se crea relacion con sucursales o se editan
        $relSucursalNueva = array_values(array_filter($sucursales, function ($sucursal) {
          return $sucursal->relacionSucursal;
        }));
        $relSucursalNueva = array_map(fn($sucursal) => $sucursal->sucursal_id, $relSucursalNueva);

        $relUsauriosSucursales = UsuarioService::listarRelUsuariosSucursales("", [
          "usuarioId" => $datos["usuarioEditadoId"]
        ]);

        $relSucursalVieja = array_map(fn($relacion) => $relacion->sucursal_id, $relUsauriosSucursales);

        // Se obtienen las lineas que se realizan insert o update
        $sucursalInsert = array_diff($relSucursalNueva, $relSucursalVieja);
        $sucursalUpdate = array_flip(array_intersect($relSucursalNueva, $relSucursalVieja));
        $sucursalDelete = array_flip(array_diff($relSucursalVieja, $relSucursalNueva));

        // Generación de relaciones
        if (!empty($sucursalInsert)) {
          foreach ($sucursalInsert as $insert) {
            $dataInsert = [
              "sucursalId"       => $insert,
              "usuarioEditadoId" => $datos["usuarioEditadoId"],
              "usuarioId"        => $datos["usuarioId"],
            ];
            UsuarioService::agregarRelacionSucursalUsuario($dataInsert);
          }
        }

        // Actualización de relaciones
        foreach ($relUsauriosSucursales as $relacion) {
          $sucursalId = $relacion->sucursal_id;

          // Actualización de relaciones (de status 300 a 200)
          if (isset($sucursalUpdate[$sucursalId]) && $relacion->status == 300) {
            UsuarioService::updateRelacionSucursalUsuario($datos, $sucursalId);
          }

          // Cambio de estado de 200 a 300 para eliminar
          if (isset($sucursalDelete[$sucursalId]) && $relacion->status == 200) {
            UsuarioService::updateRelacionSucursalUsuario($datos, $sucursalId, true);
          }
        }
      }, 5);
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioCoordinator::editarRelacionSucursales()");
      throw new UnexpectedValueException(
        "Problema en servicio editar relacion de sucursales con usuario. {$e->getMessage()} ",
        300,
        $e
      );
    }
  }

  /**
   * Coordinator que edita un perfil
   * @param array $datos
   * @return void
   */
  public static function editarPerfil($datos)
  {
    try {
      DB::transaction(function () use ($datos) {
        // Se valida el perfil
        UsuarioService::obtenerPerfilUsuario($datos["perfilId"]);

        // Se valida la clave
        $registrosPerfiles = UsuarioService::listarPerfiles("", [
          "clave"      => $datos["clave"],
          "noPerfilId" => $datos["perfilId"],
          "status"     => [UsuarioConst::PERFIL_USUARIO_STATUS_ACTIVO],
        ]);

        if (!empty($registrosPerfiles)) {
          throw new Exception("Ya existe algún perfil con esta clave, favor de revisar. {$datos["clave"]}", 300);
        }

        // Edición de perfil
        UsuarioService::editarPerfil($datos);
      }, 5);
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioCoordinator::agregar()");
      throw new UnexpectedValueException(
        "Problema en servicio agregar usuario. {$e->getMessage()} ",
        300,
        $e
      );
    }
  }

  /**
   * Coordinator que edita un perfil
   * @param string $perfilId
   * @return array
   */
  public static function obtenerPerfilUsuario($perfilId)
  {
    // Se obtiene el perfil
    $perfilObj = UsuarioService::obtenerPerfilUsuario($perfilId, false, true);

    // Se listan permisos por perfil
    $seccionesPermisos = PermisoService::listarPorPerfil($perfilId, [], ["seccion_asc"]);

    $response = [
      "seccionesPermisos" => $seccionesPermisos,
      "perfilObj"         => $perfilObj->obtenerObj(),
    ];

    return $response;
  }

  /**
   * Coordinator que edita los permisos de un perfil
   * @param array $datos
   * @return void
   */
  public static function editarPermisosPerfil($datos)
  {
    try {
      DB::transaction(function () use ($datos) {
        // Se valida el perfil
        UsuarioService::obtenerPerfilUsuario($datos["perfilId"]);

        $todosLosPermisos = [];
        foreach ($datos["permisosSeccion"] as $seccion) {
          $todosLosPermisos = array_merge($todosLosPermisos, $seccion['permisos']);
        }

        // Se validan permisos
        $idsPermisos       = array_map(fn($permiso) => $permiso["permiso_id"], $todosLosPermisos);
        $registrosPermisos = PermisoService::listar();

        if (count($idsPermisos) !== count($registrosPermisos)) {
          throw new Exception("Error al listar los permisos, favor de recargar la página.");
        }

        // Se crean nuevas relaciones
        $permisosNuevos    = array_filter($todosLosPermisos, fn($permiso) => $permiso["checkPermiso"]);
        $idsPermisosNuevos = array_map(fn($permiso) => $permiso["permiso_id"], $permisosNuevos);

        PermisoService::eliminarRelacionPerfilPermisos($datos, $datos["perfilId"], $idsPermisosNuevos);

        // Modificar los objetos usuarios de redis que tienen relacion con el perfil
        UsuarioService::editarPermisosPorPerfilEnUsuariosActivos($datos["perfilId"]);
      }, 5);
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "UsuarioCoordinator::editarPermisosPerfil()");
      throw new UnexpectedValueException(
        "Problema en servicio editar permisos de perfil usuario. {$e->getMessage()} ",
        300,
        $e
      );
    }
  }
}
