<?php

namespace App\Services;

use App\Constantes\PermisoConst;
use App\Constantes\PermisoEnVistaConst;
use App\Repositories\Action\PermisoRepoAction;
use App\Repositories\Data\PermisoRepoData;
use App\Services\BO\PermisoBO;
use App\Utilerias\HashUtils;

class PermisoService
{
  /**
   * Listar permisos por perfil, que ademas coloca los permisos en secciones
   * @param string $perfilId
   * @param array  $filtros | Filtros de consulta
   * @param array $order
   * @return array
   */
  public static function listarPorPerfil($perfilId, $filtros = [], $order = [])
  {
    return PermisoRepoData::listarPorPerfil($perfilId, $filtros, $order);
  }

  /**
   * Listar permisos
   * @param string $columnas
   * @param array $filtros
   * @param int $limit
   * @param int $offset
   * @param array $order
   * @return array
   */
  public static function listar(
    $columnas = '',
    $filtros = [],
    $limit = null,
    $offset = null,
    $order = []
  ) {
    return PermisoRepoData::listar($columnas, $filtros, $limit, $offset, $order);
  }

  /**
   * Método que elimina las relacione de perfil con permisos
   * @param array $datos
   * @param string $perfilId
   * @param array $permisosIds
   * @param bool $eliminarRelPrevias
   * @return void
   */
  public static function eliminarRelacionPerfilPermisos($datos, $perfilId, $permisosIds, $eliminarRelPrevias = true)
  {
    if ($eliminarRelPrevias) {
      PermisoRepoAction::eliminarRelPermisos($perfilId);
    }

    $insert = PermisoBO::armarInsertRelPermisos($datos, $perfilId, $permisosIds);
    PermisoRepoAction::agregarRelPermisos($insert);
  }

  /**
   * Listar permisos por usuario
   * @param string $usuarioId
   * @param array  $filtros | Filtros de consulta
   * @param array $order
   * @return array
   */
  public static function listarPorUsuario($usuarioId, $filtros = [], $order = [])
  {
    return PermisoRepoData::listarPorUsuario($usuarioId, $filtros, $order);
  }

  /**
   * Service que obtiene los permisos respectivos para cada vista
   * @param array $permisosUsuario | array de permisos que tiene el usuario
   * @param string $vista | vista a la cual se necesita
   * @return array
   */
  public static function obtenerPermisosVista($permisosUsuario, $vista)
  {
    // Inicializar permisos de vista
    $permisosDeVista = [];

    // Verificar si la vista existe en la definición
    if (isset(PermisoEnVistaConst::PERMISOS_EN_VISTA[$vista])) {
      foreach (PermisoEnVistaConst::PERMISOS_EN_VISTA[$vista] as $accion => $permiso) {
        $permisosDeVista[$accion] = in_array($permiso, $permisosUsuario);
      }
    }

    return $permisosDeVista;
  }
}
