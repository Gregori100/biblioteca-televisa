<?php

namespace App\Services\BO;

use App\Constantes\UsuarioConst;
use App\Utilerias\FechaUtils;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UsuarioBO
{
  /**
   * Método que arma insert de un usuario
   * @param array $datos
   * @return array $insert
   */
  public static function armarInsertUsuario(array $datos)
  {
    $insert = [];

    $insert["usuario"]                = $datos["usuario"];
    $insert["password"]               = Hash::make($datos["password"]);
    $insert["nombre_completo"]        = $datos["nombreCompleto"];
    $insert["email"]                  = $datos["email"];
    $insert["status"]                 = UsuarioConst::USUARIO_STATUS_ACTIVO;
    $insert["global"]                 = UsuarioConst::USUARIO_GLOBAL;
    $insert["registro_autor_id"]      = $datos["usuarioId"];
    $insert["registro_fecha"]         = FechaUtils::fechaActual();

    return $insert;
  }

  /**
   * Método que arma insert de la relación de un perfil con un usuario
   * @param array $datos
   * @return array $insert
   */
  public static function armarInsertPerfilUsuario(array $datos)
  {
    $insert = [];

    $insert["perfil_id"]         = $datos["perfilId"];
    $insert["usuario_id"]        = $datos["usuarioEditadoId"];
    $insert["status"]            = UsuarioConst::REL_PERFIL_USUARIO_ACTIVO;
    $insert["registro_autor_id"] = $datos["usuarioId"];
    $insert["registro_fecha"]    = FechaUtils::fechaActual();

    return $insert;
  }

  /**
   * Método que arma insert de la relación de una sucursal con un usuario
   * @param array $datos
   * @return array $insert
   */
  public static function armarInsertSucursalUsuario(array $datos)
  {
    $insert = [];

    $insert["sucursal_id"]       = $datos["sucursalId"];
    $insert["usuario_id"]        = $datos["usuarioEditadoId"];
    $insert["status"]            = UsuarioConst::REL_SUCURSAL_USUARIO_ACTIVO;
    $insert["registro_autor_id"] = $datos["usuarioId"];
    $insert["registro_fecha"]    = FechaUtils::fechaActual();

    return $insert;
  }

  /**
   * Método que arma update de status de rel perfil con usuario
   * @param string $status
   * @param string $usuarioId
   * @return void
   */
  public static function armarUpdateStatusRelPefilUsuario($usuarioId, $status)
  {
    $update = [];

    $update["status"]                 = $status;
    $update["actualizacion_autor_id"] = $usuarioId;
    $update["actualizacion_fecha"]    = FechaUtils::fechaActual();

    return $update;
  }

  /**
   * Método que arma update de usuario
   * @param array $datos
   * @param bool $esEdit
   * @return array
   */
  public static function armarUpdateUsuario($datos, $esEdit = true)
  {
    $update = [];

    if ($esEdit) {
      $update["usuario"]              = $datos["usuario"];
      $update["nombre_completo"]      = $datos["nombreCompleto"];
      $update["email"]                = $datos["email"];
    } else {
      $update["status"]               = UsuarioConst::USUARIO_STATUS_ELIMINADO;
    }

    $update["actualizacion_autor_id"] = $datos["usuarioId"];
    $update["actualizacion_fecha"]    = FechaUtils::fechaActual();

    return $update;
  }

  /**
   * Método que arma update de usuario
   * @param array $datos
   * @return array
   */
  public static function armarUpdatePasswordUsuario($datos)
  {
    $update = [];

    $update["password"]               = Hash::make($datos["password"]);
    $update["actualizacion_autor_id"] = $datos["usuarioId"];
    $update["actualizacion_fecha"]    = FechaUtils::fechaActual();

    return $update;
  }

  /**
   * Método que arma insert de un perfil de acceso
   * @param array $datos
   * @return array $insert
   */
  public static function armarInsertPerfil(array $datos)
  {
    $insert = [];

    $insert["clave"]             = $datos["clave"];
    $insert["titulo"]            = $datos["titulo"];
    $insert["descripcion"]       = $datos["descripcion"];
    $insert["status"]            = UsuarioConst::PERFIL_USUARIO_STATUS_ACTIVO;
    $insert["global"]            = UsuarioConst::PERFIL_GLOBAL;
    $insert["registro_autor_id"] = $datos["usuarioId"];
    $insert["registro_fecha"]    = FechaUtils::fechaActual();

    return $insert;
  }

  /**
   * Método que arma update de status de rel perfil con usuario
   * @param array $datos
   * @return array
   */
  public static function armarUpdateStatusPefilUsuario($datos)
  {
    $update = [];

    $update["status"]                 = UsuarioConst::PERFIL_USUARIO_STATUS_ELIMINADO;
    $update["actualizacion_autor_id"] = $datos["usuarioId"];
    $update["actualizacion_fecha"]    = FechaUtils::fechaActual();

    return $update;
  }

  /**
   * Método que arma update de la relación de sucursales con usuarios
   * @param array $datos
   * @param bool $eliminarRel
   * @return array
   */
  public static function armarUpdateRelSucursalUsuario(array $datos, bool $eliminarRel = false): array
  {
    $update = [];

    $update["status"] =
      $eliminarRel ? UsuarioConst::REL_SUCURSAL_USUARIO_INACTIVO : UsuarioConst::REL_SUCURSAL_USUARIO_ACTIVO;
    $update["actualizacion_autor_id"] = $datos["usuarioId"];
    $update["actualizacion_fecha"]    = FechaUtils::fechaActual();

    return $update;
  }

  /**
   * Método que arma update de perfil de acceso
   * @param array $datos
   * @return array
   */
  public static function armarUpdatePefilUsuario($datos)
  {
    $update = [];

    $update["clave"]                  = $datos["clave"];
    $update["titulo"]                 = $datos["titulo"];
    $update["descripcion"]            = $datos["descripcion"];
    $update["actualizacion_autor_id"] = $datos["usuarioId"];
    $update["actualizacion_fecha"]    = FechaUtils::fechaActual();

    return $update;
  }
}
