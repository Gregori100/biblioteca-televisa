<?php

namespace App\Repositories\Data;

use App\Constantes\UsuarioConst;
use App\Repositories\RH\PermisoRH;
use Illuminate\Support\Facades\DB;

class PermisoRepoData
{
  /**
   * Repo para listar permisos por perfil
   * @param string $perfilId
   * @param array  $filtros | Filtros de consulta
   * @param array $order
   * @return array
   * @throws Exception
   */
  public static function listarPorPerfil(
    $perfilId,
    $filtros = [],
    $order = []
  ) {
    $query = DB::table('sys_permisos AS sp')
      ->select('sp.permiso_id')
      ->leftJoin('rel_perfiles_permisos AS rpp', function ($join) use ($perfilId) {
        $join->on('sp.permiso_id', '=', 'rpp.permiso_id')
          ->where('rpp.perfil_id', '=', $perfilId);
      })
      ->leftJoin('sys_perfiles AS sperf', 'rpp.perfil_id', '=', 'sperf.perfil_id');

    PermisoRH::obtenerColumnasListarPorPerfil($query);
    PermisoRH::obtenerFiltrosListar($query, $filtros);
    PermisoRH::obtenerOrdenListar($query, $order);

    $query->groupBy(
      'sp.seccion',
      'sp.orden_seccion',
      'sp.permiso_id',
      'sp.codigo',
      'sp.titulo',
      'sp.descripcion',
      'rpp.perfil_id',
      'sperf.perfil_id',
      'sperf.clave',
      'sperf.titulo'
    );

    // Obtener resultados
    $permisos = $query->get();

    // Agrupar en PHP después de obtener los resultados
    $permisosAgrupados = $permisos->groupBy('seccion')->map(function ($grupo) {
      $checkSeccion = $grupo->every(function ($permiso) {
        return $permiso->checkPermiso;
      });

      return [
        'permisos' => $grupo->map(function ($permiso) {
          return (object)[
            'permiso_id' => $permiso->permiso_id,
            'codigo' => $permiso->codigo,
            'titulo' => $permiso->titulo,
            'descripcion' => $permiso->descripcion,
            'seccion' => $permiso->seccion,
            'orden_seccion' => $permiso->orden_seccion,
            'perfil_id' => $permiso->perfil_id,
            'perfil_clave' => $permiso->perfil_clave,
            'perfil_titulo' => $permiso->perfil_titulo,
            'checkPermiso' => (bool)$permiso->checkPermiso,
          ];
        })->toArray(),
        'checkSeccion' => $checkSeccion,
        'checkExpandir' => true
      ];
    });

    return $permisosAgrupados->toArray();
  }

  /**
   * Repo para listar todos los permisos
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
    $query = DB::table('sys_permisos AS sp')
      ->select('sp.permiso_id');

    if (!empty($limit)) {
      $query->limit($limit);
    }
    if (!empty($offset)) {
      $query->offset($offset);
    }

    PermisoRH::obtenerColumnasListar($query, $columnas);
    PermisoRH::obtenerFiltrosListar($query, $filtros);
    PermisoRH::obtenerOrdenListar($query, $order);

    return $query->get()->toArray();
  }

  /**
   * Repo para listar todos los permisos
   * @param string $columnas | Columnas que desea obtener
   * @param array  $filtros | Filtros de consulta
   * @param int $limit
   * @param int $offset
   * @param array $order
   * @return array
   * @throws Exception
   */
  public static function listarPorUsuario($usuarioId, $filtros = [], $order = [])
  {
    $query = DB::table('sys_permisos AS sp')
      ->select('sp.permiso_id')
      ->leftJoin('rel_perfiles_permisos AS rpp', 'rpp.permiso_id', '=', 'sp.permiso_id')
      ->leftJoin('rel_usuarios_perfiles AS rup', 'rup.perfil_id', '=', 'rpp.perfil_id')
      ->leftJoin('sys_usuarios AS su', 'su.usuario_id', '=', 'rup.usuario_id')
      ->where("rup.status", UsuarioConst::PERFIL_USUARIO_STATUS_ACTIVO)
      ->where("su.usuario_id", $usuarioId);

    PermisoRH::obtenerColumnasListarPorUsuario($query);
    PermisoRH::obtenerFiltrosListar($query, $filtros);
    PermisoRH::obtenerOrdenListar($query, $order);

    return $query->get()->toArray();
  }
}
