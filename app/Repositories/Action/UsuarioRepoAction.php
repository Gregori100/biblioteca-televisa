<?php

namespace App\Repositories\Action;

use App\Constantes\UsuarioConst;
use App\Utilerias\FechaUtils;
use Illuminate\Support\Facades\DB;

class UsuarioRepoAction
{
  /**
   * Repo para agregar usuarios
   * @param array $insert | Datos de insert
   * @return string $id
   */
  public static function agregar(array $insert)
  {
    $id = DB::table('sys_usuarios')
      ->insertGetId($insert, 'usuario_id');

    return $id;
  }

  /**
   * Repo para agregar la relacion de perfil con usuario
   * @param array $insert | Datos de insert
   * @return string $id
   */
  public static function agregarRelPerfilUsuario(array $insert)
  {
    DB::table('rel_usuarios_perfiles')->insert($insert);
  }

  /**
   * Repo para agregar la relacion de sucursal con usuario
   * @param array $insert | Datos de insert
   * @return string $id
   */
  public static function agregarRelSucursalUsuario(array $insert)
  {
    DB::table('rel_usuarios_sucursales')->insert($insert);
  }

  /**
   * Repo que edita un usuario
   * @param array $update
   * @param string $usuarioId
   * @return void
   */
  public static function editar($update, $usuarioId)
  {
    DB::table('sys_usuarios AS u')
      ->where('u.usuario_id', $usuarioId)
      ->update($update);
  }

  /**
   * Repo que edita rel_usuarios_perfiles
   * @param array $update
   * @param string $relUsuarioPerfilId
   * @return void
   */
  public static function editarRelPefilUsuario($update, $relUsuarioPerfilId)
  {
    DB::table('rel_usuarios_perfiles AS rup')
      ->where('rup.rel_usuario_perfil_id', $relUsuarioPerfilId)
      ->update($update);
  }

  /**
   * Repo para agregar sys_perfiles
   * @param array $insert | Datos de insert
   * @return string $id
   */
  public static function agregarPerfil(array $insert)
  {
    $id = DB::table('sys_perfiles')
      ->insertGetId($insert, 'perfil_id');

    return $id;
  }

  /**
   * Repo que edita un perfil de usuario
   * @param array $update
   * @param string $perfilId
   * @return void
   */
  public static function editarPerfil($update, $perfilId)
  {
    DB::table('sys_perfiles AS p')
      ->where('p.perfil_id', $perfilId)
      ->update($update);
  }

  /**
   * Repo que edita la relación de una sucursal con un usuario
   * @param array $update
   * @param string $usuarioEditadoId
   * @param string $sucursalId
   * @return void
   */
  public static function editarRelSucursalUsuario($update, $usuarioEditadoId, $sucursalId)
  {
    DB::table('rel_usuarios_sucursales AS rus')
      ->where('rus.usuario_id', $usuarioEditadoId)
      ->where('rus.sucursal_id', $sucursalId)
      ->update($update);
  }

  /**
   * Repo que edita en eliminado todas las fotografias de un usuario
   * @param array $datos[usuarioEditadoId,usuarioId]
   * @return void
   */
  public static function editarUsuariosFotografias($datos)
  {
    DB::table('usuarios_fotografias AS uf')
      ->where('uf.usuario_id', $datos["usuarioEditadoId"])
      ->where('uf.status', UsuarioConst::USUARIO_FOTOGRAFIA_STATUS_ACTIVO)
      ->update([
        'status'                 => UsuarioConst::USUARIO_FOTOGRAFIA_STATUS_ELIMINADO,
        'actualizacion_autor_id' => $datos["usuarioId"],
        'actualizacion_fecha'    => FechaUtils::fechaActual()
      ]);
  }

  /**
   * Repo para agregar usuarios fotografias
   * @param array $insert | Datos de insert
   * @return string $id
   */
  public static function agregarUsuariosFotografias(array $insert)
  {
    $id = DB::table('usuarios_fotografias')
      ->insertGetId($insert, 'usuario_fotografia_id');
    return $id;
  }
}
