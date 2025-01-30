<?php

namespace App\Repositories\Action;

use Illuminate\Support\Facades\DB;

class PermisoRepoAction
{
  /**
   * Metodo para eliminar relacion perfiles - permisos
   * @param array $perfilId
   */
  public static function eliminarRelPermisos($perfilId)
  {
    return DB::table('rel_perfiles_permisos')
      ->where('perfil_id', $perfilId)
      ->delete();
  }

  /**
   * Método para agregar relacion perfiles - permisos
   * @param array $insert
   * @throws Exception $e
   */
  public static function agregarRelPermisos($insert)
  {
    DB::table('rel_perfiles_permisos')->insert($insert);
  }
}
