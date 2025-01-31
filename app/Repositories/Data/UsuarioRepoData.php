<?php

namespace App\Repositories\Data;

use App\Constantes\UsuarioConst;
use App\Repositories\RH\UsuarioRH;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioRepoData
{
  /**
   * Repo para listar usuarios
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
    $query = DB::table('sys_usuarios AS u')
      ->select('u.usuario_id')
      ->where('u.global', '=', 'true')
      ->where('rup.status', 200)
      ->leftJoin("sys_usuarios AS su1", "su1.usuario_id", "u.registro_autor_id")
      ->leftJoin("sys_usuarios AS su2", "su2.usuario_id", "u.actualizacion_autor_id")
      ->leftJoin("rel_usuarios_perfiles AS rup", "rup.usuario_id", "u.usuario_id")
      ->leftJoin("sys_perfiles AS p", "p.perfil_id", "rup.perfil_id");

    if (!empty($limit)) {
      $query->limit($limit);
    }
    if (!empty($offset)) {
      $query->offset($offset);
    }

    UsuarioRH::obtenerColumnasListar($query, $columnas);
    UsuarioRH::obtenerFiltrosListar($query, $filtros);
    UsuarioRH::obtenerOrdenListar($query, $order);

    return $query->get()->toArray();
  }

  /**
   * Repo para listar perfiles de usuarios
   * @param string $columnas | Columnas que desea obtener
   * @param array  $filtros | Filtros de consulta
   * @param int $limit
   * @param int $offset
   * @param array $order
   * @return array $compras
   * @throws Exception
   */
  public static function listarPerfiles(
    $columnas = '',
    $filtros = [],
    $limit = null,
    $offset = null,
    $order = []
  ) {
    $query = DB::table('sys_perfiles AS p')
      ->select('p.perfil_id')
      ->where('p.global', '=', 'true')
      ->leftJoin("sys_usuarios AS su1", "su1.usuario_id", "p.registro_autor_id")
      ->leftJoin("sys_usuarios AS su2", "su2.usuario_id", "p.actualizacion_autor_id");

    if (!empty($limit)) {
      $query->limit($limit);
    }
    if (!empty($offset)) {
      $query->offset($offset);
    }

    UsuarioRH::obtenerColumnasListarPerfiles($query, $columnas);
    UsuarioRH::obtenerFiltrosListarPerfiles($query, $filtros);
    UsuarioRH::obtenerOrdenListarPerfiles($query, $order);

    return $query->get()->toArray();
  }

  /**
   * Repo para validar credenciales de un usuario
   * @param string $usuario
   * @param string  $password
   * @return array $compras
   */
  public static function validarUsuario($usuario, $password)
  {
    $registro = DB::table("sys_usuarios AS u")
      ->select(
        "u.usuario_id",
        "u.usuario",
        "u.password",
        "u.nombre_completo",
        "u.nombre_corto",
        "u.email",
        "u.telefono",
        "u.fecha_ultimo_acceso",
        "u.status",
        "u.global",
        "u.registro_autor_id",
        "u.registro_fecha",
        "u.actualizacion_autor_id",
        "u.actualizacion_fecha",
      )
      ->where('u.usuario', $usuario)
      ->where('u.status', UsuarioConst::USUARIO_STATUS_ACTIVO)
      ->get()->first();

    // Verifica si el usuario existe y la contraseña es correcta
    if ($registro && Hash::check($password, $registro->password)) {
      return $registro;
    }

    return null;
  }

  /**
   * Repo para listarRelUsuariosPerfiles
   * @param string $columnas | Columnas que desea obtener
   * @param array  $filtros | Filtros de consulta
   * @param int $limit
   * @param int $offset
   * @param array $order
   * @return array $compras
   * @throws Exception
   */
  public static function ListarRelUsuariosPerfiles(
    $columnas = '',
    $filtros = [],
    $limit = null,
    $offset = null,
    $order = []
  ) {
    $query = DB::table('rel_usuarios_perfiles AS rup')
      ->select('rup.usuario_id');

    if (!empty($limit)) {
      $query->limit($limit);
    }
    if (!empty($offset)) {
      $query->offset($offset);
    }

    UsuarioRH::obtenerColumnasListarRelUsuariosPerfiles($query, $columnas);
    UsuarioRH::obtenerFiltrosListarRelUsuariosPerfiles($query, $filtros);
    UsuarioRH::obtenerOrdenListarRelUsuariosPerfiles($query, $order);

    return $query->get()->toArray();
  }

  /**
   * Repo para listarRelUsuariosSucursales
   * @param string $columnas | Columnas que desea obtener
   * @param array  $filtros | Filtros de consulta
   * @param int $limit
   * @param int $offset
   * @param array $order
   * @return array $compras
   * @throws Exception
   */
  public static function listarRelUsuariosSucursales(
    $columnas = '',
    $filtros = [],
    $limit = null,
    $offset = null,
    $order = []
  ) {
    $query = DB::table('rel_usuarios_sucursales AS rus')
      ->select('rus.rel_usuario_sucursal_id')
      ->leftJoin("sys_usuarios AS u", "u.usuario_id", "rus.usuario_id");

    if (!empty($limit)) {
      $query->limit($limit);
    }
    if (!empty($offset)) {
      $query->offset($offset);
    }

    UsuarioRH::obtenerColumnasListarRelUsuariosSucursales($query, $columnas);
    UsuarioRH::obtenerFiltrosListarRelUsuariosSucursales($query, $filtros);
    UsuarioRH::obtenerOrdenListarRelUsuariosSucursales($query, $order);

    return $query->get()->toArray();
  }
}
