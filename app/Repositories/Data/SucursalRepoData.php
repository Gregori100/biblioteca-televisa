<?php

namespace App\Repositories\Data;

use App\Repositories\RH\SucursalRH;
use Illuminate\Support\Facades\DB;

class SucursalRepoData
{
  /**
   * Repo para obtener sucursales
   * @param string $columnas | Columnas que desea obtener
   * @param array  $filtros | Filtros de consulta
   * @param int $limit
   * @param int $offset
   * @param array $order
   * @return array $compras
   * @throws Exception
   */
  public static function listar(
    $columnas = '',
    $filtros = [],
    $limit = null,
    $offset = null,
    $order = []
  ) {
    $query = DB::table('cat_sucursales AS s')
      ->select('sucursal_id')
      ->leftJoin("sys_usuarios AS su1", "su1.usuario_id", "s.registro_autor_id")
      ->leftJoin("sys_usuarios AS su2", "su2.usuario_id", "s.actualizacion_autor_id");

    if (!empty($limit)) {
      $query->limit($limit);
    }
    if (!empty($offset)) {
      $query->offset($offset);
    }

    SucursalRH::obtenerColumnasListar($query, $columnas);
    SucursalRH::obtenerFiltrosListar($query, $filtros);
    SucursalRH::obtenerOrdenListar($query, $order);

    return $query->get()->toArray();
  }
}
