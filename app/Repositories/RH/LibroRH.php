<?php

namespace App\Repositories\RH;

use App\Utilerias\QueryUtils;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class LibroRH
{
  /*********************************************************/
  /************************* Libro *************************/
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
          case 'libroId':
            $query->addSelect('l.libro_id');
            break;
        }
      }
    } else {
      $query->addSelect(
        "l.libro_id",
        "l.folio",
        "l.nombre",
        "l.autor_nombre",
        "l.editorial_nombre",
        "l.numero_paginas",
        "l.genero",
        "l.idioma",
        "l.isbn",
        "l.salida_fecha",
        "l.regreso_fecha",
        "l.observaciones",
        "l.motivo_eliminacion",
        "l.status",
        DB::raw(
          "(CASE
          WHEN l.status = 200 THEN 'Activo'
          WHEN l.status = 300 THEN 'Eliminado'
          ELSE '' END
          ) AS status_nombre"
        ),
        "l.status_disponibilidad",
        "l.registro_autor_id",
        "l.registro_fecha",
        "l.actualizacion_autor_id",
        "l.actualizacion_fecha",
        // Auditoria
        // "su1.nombre_completo AS registro_autor",
        // "su2.nombre_completo AS actualizacion_autor",
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
    if (!empty($filtros['busqueda'])) {
      $query->where(function ($query) use ($filtros) {
        $query->whereRaw(
          'unaccent(l.folio::text) ilike unaccent(?) OR
          unaccent(l.nombre) ilike unaccent(?) OR
          unaccent(l.autor_nombre) ilike unaccent(?) OR
          unaccent(l.genero) ilike unaccent(?) OR
          unaccent(l.idioma) ilike unaccent(?) OR
          unaccent(l.editorial_nombre) ilike unaccent(?) OR
          unaccent(l.isbn) ilike unaccent(?)',
          QueryUtils::busquedaIlike($filtros['busqueda'], 7)
        );
      });
    }

    if (!empty($filtros['busquedaAutor'])) {
      $query->where(function ($query) use ($filtros) {
        $query->whereRaw(
          'unaccent(l.autor_nombre) ilike unaccent(?)',
          QueryUtils::busquedaIlike($filtros['busquedaAutor'], 1)
        );
      });
    }

    if (!empty($filtros['busquedaEditorial'])) {
      $query->where(function ($query) use ($filtros) {
        $query->whereRaw(
          'unaccent(l.editorial_nombre) ilike unaccent(?)',
          QueryUtils::busquedaIlike($filtros['busquedaEditorial'], 1)
        );
      });
    }

    if (!empty($filtros['busquedaGenero'])) {
      $query->where(function ($query) use ($filtros) {
        $query->whereRaw(
          'unaccent(l.genero) ilike unaccent(?)',
          QueryUtils::busquedaIlike($filtros['busquedaGenero'], 1)
        );
      });
    }

    if (!empty($filtros['busquedaIdioma'])) {
      $query->where(function ($query) use ($filtros) {
        $query->whereRaw(
          'unaccent(l.idioma) ilike unaccent(?)',
          QueryUtils::busquedaIlike($filtros['busquedaIdioma'], 1)
        );
      });
    }

    if (!empty($filtros['busquedaIsbn'])) {
      $query->where(function ($query) use ($filtros) {
        $query->whereRaw(
          'unaccent(l.isbn) ilike unaccent(?)',
          QueryUtils::busquedaIlike($filtros['busquedaIsbn'], 1)
        );
      });
    }

    if (!empty($filtros['status'])) {
      $query->whereIn('l.status', $filtros['status']);
    }

    if (!empty($filtros['statusDisponibilidad'])) {
      $query->whereIn('l.status_disponibilidad', $filtros['statusDisponibilidad']);
    }

    if (!empty($filtros['nombre'])) {
      $query->where('l.nombre', $filtros['nombre']);
    }

    if (!empty($filtros['libroIdNot'])) {
      $query->where('l.libro_id', '!=', $filtros['libroIdNot']);
    }

    if (!empty($filtros['libroId'])) {
      $query->where('l.libro_id', $filtros['libroId']);
    }

    // Fecha regreso
    if ((isset($filtros['fechaRegresoInicial']) && !empty($filtros['fechaRegresoInicial'])) ||
      isset($filtros['fechaRegresoFinal']) && !empty($filtros['fechaRegresoFinal'])
    ) {
      $fechaRegresoI = isset($filtros['fechaRegresoInicial']) &&
        !empty($filtros['fechaRegresoInicial']) ? $filtros["fechaRegresoInicial"] : "";
      $fechaRegresoF = isset($filtros['fechaRegresoFinal']) &&
        !empty($filtros['fechaRegresoFinal']) ? $filtros["fechaRegresoFinal"] : "";
      QueryUtils::queryFecha($query, 'l.regreso_fecha', $fechaRegresoI, $fechaRegresoF);
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
        $query->orderBy('l.registro_fecha', 'asc');
      }

      if ($orden == 'registro_fecha_desc') {
        $query->orderBy('l.registro_fecha', 'desc');
      }

      if ($orden == 'folio_asc') {
        $query->orderBy('l.folio', 'asc');
      }

      if ($orden == 'folio_desc') {
        $query->orderBy('l.folio', 'desc');
      }
    }

    $query->orderBy('l.libro_id'); // Order by determinístico
  }
}
