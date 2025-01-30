<?php

namespace App\Services\BO;

use App\Utilerias\FechaUtils;

class PermisoBO
{
  /**
   * Método que arma insert de un usuario
   * @param array $datos
   * @return array $insert
   */
  public static function armarInsertRelPermisos(array $datos, $perfilId, $permisosIds)
  {
    $inserts = [];

    foreach ($permisosIds as $permisoId) {
      $insert["perfil_id"]              = $perfilId;
      $insert["permiso_id"]             = $permisoId;
      $insert["registro_fecha"]         = FechaUtils::fechaActual();
      $insert["registro_autor_id"]      = $datos["usuarioId"];

      $inserts[] = $insert;
    }

    return $inserts;
  }
}
