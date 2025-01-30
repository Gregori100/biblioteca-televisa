<?php

namespace App\Services\BO;

use App\Constantes\SucursalConst;
use App\Utilerias\FechaUtils;

class SucursalBO
{
  /**
   * Método que arma insert de una sucursal
   * @param array $datos
   * @return array $insert
   */
  public static function armarInsertSucursal(array $datos): array
  {
    $insert = [];

    $insert["clave"]             = $datos["clave"];
    $insert["titulo"]            = $datos["titulo"];
    $insert["cp"]                = $datos["cp"];
    $insert["direccion_calle"]   = $datos["direccionCalle"];
    $insert["direccion_colonia"] = $datos["direccionColonia"];
    $insert["telefono"]          = $datos["telefono"];
    $insert["zona_horaria"]      = $datos["zonaHoraria"];
    $insert["status"]            = SucursalConst::SUCURSAL_STATUS_ACTIVO;
    $insert["default"]           = SucursalConst::SUCURSAL_NO_DEFAULT;
    $insert["registro_autor_id"] = $datos["usuarioId"];
    $insert["registro_fecha"]    = FechaUtils::fechaActual();

    return $insert;
  }

  /**
   * Método que arma update de una sucursal
   * @param array $datos
   * @param bool $esEdit
   * @return array $update
   */
  public static function armarUpdateSucursal(array $datos, bool $esEdit = false): array
  {
    $update = [];

    if ($esEdit) {
      $update["clave"]                  = $datos["clave"];
      $update["titulo"]                 = $datos["titulo"];
      $update["cp"]                     = $datos["cp"];
      $update["direccion_calle"]        = $datos["direccionCalle"];
      $update["direccion_colonia"]      = $datos["direccionColonia"];
      $update["telefono"]               = $datos["telefono"];
      $update["zona_horaria"]           = $datos["zonaHoraria"];
    } else {
      $update["status"]               = SucursalConst::SUCURSAL_STATUS_ELIMINADO;
    }

    $update["actualizacion_autor_id"] = $datos["usuarioId"];
    $update["actualizacion_fecha"]    = FechaUtils::fechaActual();

    return $update;
  }
}
