<?php

namespace App\Repositories\Action;

use Illuminate\Support\Facades\DB;

class SucursalRepoAction
{
  /**
   * Repo para agregar sucursales
   * @param array $insert | Datos de insert
   * @return string $id
   */
  public static function agregar(array $insert)
  {
    $id = DB::table('cat_sucursales')
      ->insertGetId($insert, 'sucursal_id');

    return $id;
  }

  /**
   * Repo que edita una sucursal
   * @param array $update
   * @param string $sucursalId
   * @return void
   */
  public static function editar($update, $sucursalId)
  {
    DB::table('cat_sucursales AS s')
      ->where('s.sucursal_id', $sucursalId)
      ->update($update);
  }
}
