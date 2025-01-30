<?php

namespace App\Repositories\RH;

use App\Utilerias\QueryUtils;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class SucursalRH
{
  /*********************************************************/
  /********************** Sucursales ***********************/
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
          case 'sucursalId':
            $query->addSelect('s.sucursal_id');
            break;
        }
      }
    } else {
      $query->addSelect(
        "s.sucursal_id",
        "s.clave",
        "s.titulo",
        "s.cp",
        "s.direccion_calle",
        "s.direccion_colonia",
        "s.telefono",
        "s.zona_horaria",
        DB::raw(
          "(CASE
              WHEN s.zona_horaria = 'PT' THEN 'Tiempo del Pacífico (PT)'
              WHEN s.zona_horaria = 'CT' THEN 'Tiempo del Centro (CT)'
              WHEN s.zona_horaria = 'ET' THEN 'Tiempo del Este (ET)'
              ELSE '' END
            ) AS zona_horaria_descripcion"
        ),
        "s.documento_encabezado",
        "s.documento_pie_pagina",
        "s.status",
        DB::raw(
          "(CASE
              WHEN s.status = 200 THEN 'Activo'
              WHEN s.status = 300 THEN 'Eliminado'
              ELSE '' END
            ) AS status_nombre"
        ),
        "s.metadatos",
        "s.default",
        "s.registro_autor_id",
        "s.registro_fecha",
        "s.actualizacion_autor_id",
        "s.actualizacion_fecha",
        // Auditoria
        "su1.nombre_completo AS registro_autor",
        "su2.nombre_completo AS actualizacion_autor",
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
    if (!empty($filtros['sucursalId'])) {
      $query->where('s.sucursal_id', $filtros['sucursalId']);
    }

    if (!empty($filtros['default'])) {
      $query->where('s.default', "=", true);
    }

    if (!empty($filtros['busqueda'])) {
      $query->where(function ($query) use ($filtros) {
        $query->whereRaw(
          'unaccent(s.titulo) ilike unaccent(?) OR
          unaccent(s.clave) ilike unaccent(?)',
          QueryUtils::busquedaIlike($filtros['busqueda'], 2)
        );
      });
    }

    if (!empty($filtros['clave'])) {
      $query->where('s.clave', $filtros['clave']);
    }

    if (!empty($filtros['status'])) {
      $query->where('s.status', $filtros['status']);
    }

    if (!empty($filtros['sucursalesIds'])) {
      $query->whereIn('s.sucursal_id', $filtros['sucursalesIds']);
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
      if ($orden == 'registro_fecha_asc') {
        $query->orderBy('s.registro_fecha', 'asc');
      }

      if ($orden == 'registro_fecha_desc') {
        $query->orderBy('s.registro_fecha', 'desc');
      }
    }
  }
}
