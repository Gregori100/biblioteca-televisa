<?php

namespace App\Repositories\RH;

use App\Utilerias\QueryUtils;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class PermisoRH
{
  /*********************************************************/
  /************************ Usuarios ***********************/
  /*********************************************************/
  /**
   * Método para obtener las columnas del método para listar
   * @param Builder $query
   * @param string $columnas
   */
  public static function obtenerColumnasListar(Builder &$query, $columnas)
  {
    if (!empty($columnas)) {
      $columnas = explode(',', $columnas);

      foreach ($columnas as $columna) {
        switch ($columna) {
          case 'permisoId':
            $query->addSelect('sp.permiso_id');
            break;
        }
      }
    } else {
      $query->addSelect(
        "sp.permiso_id",
        "sp.codigo",
        "sp.titulo",
        "sp.descripcion",
        "sp.seccion",
      );
    }
  }

  /**
   * Método para agregar filtros al método listar
   * @param Builder $query
   * @param array $filtros
   */
  public static function obtenerFiltrosListar(Builder &$query, array $filtros)
  {
    if (!empty($filtros['permisoId'])) {
      $query->where('sp.permiso_id', $filtros['permisoId']);
    }

    if (!empty($filtros['codigo'])) {
      $query->where('sp.codigo', $filtros['codigo']);
    }

    if (!empty($filtros['permisosIds'])) {
      $query->whereIn('sp.permiso_id', $filtros['permisosIds']);
    }
  }

  /**
   * Método para ordenar las columnas
   * @param Builder $query
   * @param array $order
   */
  public static function obtenerOrdenListar(Builder &$query, array $order)
  {
    foreach ($order as $orden) {
      if ($orden == 'seccion_titulo_asc') {
        $query->orderBy('sp.seccion', 'asc');
        $query->orderBy('sp.titulo', 'asc');
      }

      if ($orden == 'seccion_titulo_desc') {
        $query->orderBy('sp.seccion', 'desc');
        $query->orderBy('sp.titulo', 'desc');
      }

      if ($orden == 'seccion_asc') {
        $query->orderBy('sp.orden_seccion', 'asc');
        $query->orderBy('sp.titulo', 'asc');
      }

      if ($orden == 'seccion_desc') {
        $query->orderBy('sp.seccion', 'desc');
        $query->orderBy('sp.titulo', 'desc');
      }

      if ($orden == 'titulo_asc') {
        $query->orderBy('sp.titulo', 'asc');
      }

      if ($orden == 'titulo_desc') {
        $query->orderBy('sp.titulo', 'desc');
      }
    }
  }

  /*********************************************************/
  /****************** LISTADO POR PERFIL *******************/
  /*********************************************************/
  /**
   * Método para obtener las columnas del método para listar
   * @param Builder $query
   */
  public static function obtenerColumnasListarPorPerfil(Builder &$query)
  {
    $query->addSelect(
      "sp.permiso_id",
      "sp.codigo",
      "sp.titulo",
      "sp.descripcion",
      "sp.seccion",
      "sp.orden_seccion",
      "sperf.perfil_id",
      "sperf.clave AS perfil_clave",
      "sperf.titulo AS perfil_titulo",
      DB::raw('CASE WHEN rpp.perfil_id IS NOT NULL THEN TRUE ELSE FALSE END AS "checkPermiso"')
    );
  }

  /*********************************************************/
  /****************** LISTADO POR USUARIO ******************/
  /*********************************************************/
  /**
   * Método para obtener las columnas del método para listar
   * @param Builder $query
   */
  public static function obtenerColumnasListarPorUsuario(Builder &$query)
  {
    $query->addSelect(
      "sp.permiso_id",
      "sp.codigo",
      "sp.titulo",
      "sp.descripcion",
      "sp.seccion",
      "sp.orden_seccion",
    );
  }
}
