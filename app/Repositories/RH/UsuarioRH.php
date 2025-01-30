<?php

namespace App\Repositories\RH;

use App\Utilerias\QueryUtils;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class UsuarioRH
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
          case 'usuarioId':
            $query->addSelect('u.usuario_id');
            break;
        }
      }
    } else {
      $query->addSelect(
        "u.usuario_id",
        "u.usuario",
        "u.password",
        "u.nombre_completo",
        "u.nombre_corto",
        "u.email",
        "u.telefono",
        "u.fecha_ultimo_acceso",
        "u.status",
        DB::raw(
          "(CASE
              WHEN u.status = 200 THEN 'Activo'
              WHEN u.status = 300 THEN 'Eliminado'
              ELSE '' END
            ) AS status_nombre"
        ),
        "u.global",
        "u.registro_autor_id",
        "u.registro_fecha",
        "u.actualizacion_autor_id",
        "u.actualizacion_fecha",
        // Perfil
        "p.titulo AS perfil_titulo",
        "p.perfil_id",
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
    if (!empty($filtros['usuarioId'])) {
      $query->where('u.usuario_id', $filtros['usuarioId']);
    }

    if (!empty($filtros['usuario'])) {
      $query->where('u.usuario', $filtros['usuario']);
    }

    if (!empty($filtros['busqueda'])) {
      $query->where(function ($query) use ($filtros) {
        $query->whereRaw(
          'unaccent(u.usuario) ilike unaccent(?) OR
          unaccent(u.nombre_completo) ilike unaccent(?) OR
          unaccent(u.nombre_corto) ilike unaccent(?)',
          QueryUtils::busquedaIlike($filtros['busqueda'], 3)
        );
      });
    }

    if (!empty($filtros['statusRelacionPerfilUsuario'])) {
      $query->whereIn('rup.status', $filtros['statusRelacionPerfilUsuario']);
    }

    if (!empty($filtros['perfilId'])) {
      $query->where('p.perfil_id', $filtros['perfilId']);
    }

    if (!empty($filtros['status'])) {
      $query->where('u.status', $filtros['status']);
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
        $query->orderBy('u.registro_fecha', 'asc');
      }

      if ($orden == 'registro_fecha_desc') {
        $query->orderBy('u.registro_fecha', 'desc');
      }
    }
  }

  /*********************************************************/
  /************************ Perfiles ***********************/
  /*********************************************************/
  /**
   * Método para obtener las columnas del método para listar
   * @param Builder $query
   * @param string $columnas
   */
  public static function obtenerColumnasListarPerfiles(Builder &$query, $columnas)
  {
    if (!empty($columnas)) {
      $columnas = explode(',', $columnas);

      foreach ($columnas as $columna) {
        switch ($columna) {
          case 'perfilId':
            $query->addSelect('p.perfil_id');
            break;
        }
      }
    } else {
      $query->addSelect(
        "p.perfil_id",
        "p.clave",
        "p.titulo",
        "p.descripcion",
        "p.status",
        DB::raw(
          "(CASE
              WHEN p.status = 200 THEN 'Activo'
              WHEN p.status = 300 THEN 'Eliminado'
              ELSE '' END
            ) AS status_nombre"
        ),
        "p.global",
        "p.registro_autor_id",
        "p.registro_fecha",
        "p.actualizacion_autor_id",
        "p.actualizacion_fecha",
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
  public static function obtenerFiltrosListarPerfiles(Builder &$query, array $filtros)
  {
    if (!empty($filtros['perfilId'])) {
      $query->where('p.perfil_id', $filtros['perfilId']);
    }

    if (!empty($filtros['busqueda'])) {
      $query->where(function ($query) use ($filtros) {
        $query->whereRaw(
          'unaccent(p.clave) ilike unaccent(?) OR
          unaccent(p.titulo) ilike unaccent(?) OR
          unaccent(p.descripcion) ilike unaccent(?)',
          QueryUtils::busquedaIlike($filtros['busqueda'], 3)
        );
      });
    }

    if (!empty($filtros['clave'])) {
      $query->where('p.clave', $filtros['clave']);
    }

    if (!empty($filtros['status'])) {
      $query->whereIn('p.status', $filtros['status']);
    }

    if (!empty($filtros['noPerfilId'])) {
      $query->where('p.perfil_id', "!=", $filtros['noPerfilId']);
    }
  }

  /**
   * Método para ordenar las columnas
   * @param Builder $query
   * @param array $order
   */
  public static function obtenerOrdenListarPerfiles(Builder &$query, array $order)
  {
    foreach ($order as $orden) {
      if ($orden == 'registro_fecha_asc') {
        $query->orderBy('p.registro_fecha', 'asc');
      }

      if ($orden == 'registro_fecha_desc') {
        $query->orderBy('p.registro_fecha', 'desc');
      }
    }
  }

  /*********************************************************/
  /***************** Rel Perfiles Usuarios *****************/
  /*********************************************************/
  /**
   * Método para obtener las columnas del método para listar
   * @param Builder $query
   * @param string $columnas
   */
  public static function obtenerColumnasListarRelUsuariosPerfiles(Builder &$query, $columnas)
  {
    if (!empty($columnas)) {
      $columnas = explode(',', $columnas);

      foreach ($columnas as $columna) {
        switch ($columna) {
          case 'relUsuarioPerfilId':
            $query->addSelect('rup.rel_usuario_perfil_id');
            break;
        }
      }
    } else {
      $query->addSelect(
        "rup.rel_usuario_perfil_id",
        "rup.perfil_id",
        "rup.usuario_id",
        "rup.status",
        "rup.registro_autor_id",
        "rup.registro_fecha",
        "rup.actualizacion_autor_id",
        "rup.actualizacion_fecha",
      );
    }
  }

  /**
   * Método para agregar filtros al método listar
   * @param Builder $query
   * @param array $filtros
   */
  public static function obtenerFiltrosListarRelUsuariosPerfiles(Builder &$query, array $filtros)
  {
    if (!empty($filtros['perfilId'])) {
      $query->where('rup.perfil_id', $filtros['perfilId']);
    }

    if (!empty($filtros['usuarioId'])) {
      $query->where('rup.usuario_id', $filtros['usuarioId']);
    }

    if (!empty($filtros['status'])) {
      $query->whereIn('rup.status', $filtros['status']);
    }
  }

  /**
   * Método para ordenar las columnas
   * @param Builder $query
   * @param array $order
   */
  public static function obtenerOrdenListarRelUsuariosPerfiles(Builder &$query, array $order)
  {
    foreach ($order as $orden) {
      if ($orden == 'registro_fecha_asc') {
        $query->orderBy('rup.registro_fecha', 'asc');
      }

      if ($orden == 'registro_fecha_desc') {
        $query->orderBy('rup.registro_fecha', 'desc');
      }
    }
  }

  /*********************************************************/
  /**************** Rel Usuarios Sucursales ****************/
  /*********************************************************/
  /**
   * Método para obtener las columnas del método para listar
   * @param Builder $query
   * @param string $columnas
   */
  public static function obtenerColumnasListarRelUsuariosSucursales(Builder &$query, $columnas)
  {
    if (!empty($columnas)) {
      $columnas = explode(',', $columnas);

      foreach ($columnas as $columna) {
        switch ($columna) {
          case 'relUsuarioSucursalId':
            $query->addSelect('rus.rel_usuario_sucursal_id');
            break;
        }
      }
    } else {
      $query->addSelect(
        "rus.rel_usuario_sucursal_id",
        "rus.usuario_id",
        "rus.sucursal_id",
        "rus.status",
        "rus.registro_autor_id",
        "rus.registro_fecha",
        "rus.actualizacion_autor_id",
        "rus.actualizacion_fecha",
      );
    }
  }

  /**
   * Método para agregar filtros al método listar
   * @param Builder $query
   * @param array $filtros
   */
  public static function obtenerFiltrosListarRelUsuariosSucursales(Builder &$query, array $filtros)
  {
    if (!empty($filtros['sucursalId'])) {
      $query->where('rus.sucursal_id', $filtros['sucursalId']);
    }

    if (!empty($filtros['usuarioId'])) {
      $query->where('rus.usuario_id', $filtros['usuarioId']);
    }

    if (!empty($filtros['status'])) {
      $query->whereIn('rus.status', $filtros['status']);
    }

    if (!empty($filtros['statusUsuario'])) {
      $query->whereIn('u.status', $filtros['statusUsuario']);
    }
  }

  /**
   * Método para ordenar las columnas
   * @param Builder $query
   * @param array $order
   */
  public static function obtenerOrdenListarRelUsuariosSucursales(Builder &$query, array $order)
  {
    foreach ($order as $orden) {
      if ($orden == 'registro_fecha_asc') {
        $query->orderBy('rus.registro_fecha', 'asc');
      }

      if ($orden == 'registro_fecha_desc') {
        $query->orderBy('rus.registro_fecha', 'desc');
      }
    }
  }
}
