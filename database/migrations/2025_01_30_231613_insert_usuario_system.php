<?php

use App\Utilerias\FechaUtils;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    // Insertar el primer de usuario
    DB::table('sys_usuarios')->insert([
      // 'usuario_id' => 1,
      'usuario' => 'system',
      'password' =>
      '$2y$12$t.liwlg2r1Lvhn4.kATA3uaZ0e2PZosDIkNHP2u39XzZo.zIOt3YC',
      'nombre_completo' => 'System',
      'nombre_corto' => 'System',
      'email' => 'system@demo.com',
      'status' => 200,
      'global' => false,
    ]);
    DB::table('sys_usuarios')->insert([
      // 'usuario_id' => 2,
      'usuario' => 'Administrador',
      'password' =>
      '$2y$12$t.liwlg2r1Lvhn4.kATA3uaZ0e2PZosDIkNHP2u39XzZo.zIOt3YC',
      'nombre_completo' => 'Administrador',
      'nombre_corto' => 'Administrador',
      'email' => 'system@demo.com',
      'status' => 200,
      'global' => true,
      'registro_autor_id' => '1',
      'registro_fecha' => FechaUtils::fechaActual(),
    ]);

    // Insertar la primera sucursal
    DB::table('cat_sucursales')->insert([
      // 'sucursal_id' => 1,
      'clave' => 'MTZ',
      'titulo' => '',
      'cp' => '',
      'direccion_calle' => '',
      'direccion_colonia' => '',
      'telefono' => '',
      'zona_horaria' => 'CT',
      'status' => 200,
      'default' => true,
      'registro_autor_id' => '1',
      'registro_fecha' => FechaUtils::fechaActual(),
    ]);

    // Insertar rel_usuarios_sucursales
    DB::table('rel_usuarios_sucursales')->insert([
      'usuario_id' => '1',
      'sucursal_id' => '1',
      'status' => 200,
      'registro_autor_id' => '1',
      'registro_fecha' => FechaUtils::fechaActual(),
    ]);
    DB::table('rel_usuarios_sucursales')->insert([
      'usuario_id' => '2',
      'sucursal_id' => '1',
      'status' => 200,
      'registro_autor_id' => '1',
      'registro_fecha' => FechaUtils::fechaActual(),
    ]);

    // Insertar sys_perfiles
    DB::table('sys_perfiles')->insert([
      // 'perfil_id' => 1,
      'clave' => 'MASTER',
      'titulo' => 'Perfil Master',
      'descripcion' => 'Perfil master con acceso total del sistema',
      'status' => 200,
      'global' => false,
      'registro_autor_id' => '1',
      'registro_fecha' => FechaUtils::fechaActual(),
    ]);
    DB::table('sys_perfiles')->insert([
      // 'perfil_id' => 2,
      'clave' => 'ADMINISTRADOR',
      'titulo' => 'Perfil administrador',
      'descripcion' => 'Perfil administrador con acceso total del sistema',
      'status' => 200,
      'global' => true,
      'registro_autor_id' => '1',
      'registro_fecha' => FechaUtils::fechaActual(),
    ]);

    // Insertar rel_usuarios_perfiles
    DB::table('rel_usuarios_perfiles')->insert([
      'perfil_id' => '1',
      'usuario_id' => '1',
      'status' => 200,
      'registro_autor_id' => '1',
      'registro_fecha' => FechaUtils::fechaActual(),
    ]);
    DB::table('rel_usuarios_perfiles')->insert([
      'perfil_id' => '2',
      'usuario_id' => '2',
      'status' => 200,
      'registro_autor_id' => '1',
      'registro_fecha' => FechaUtils::fechaActual(),
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Eliminar la relación usuario-perfil
    DB::table('rel_usuarios_perfiles')
      ->where('rel_usuario_perfil_id', '2')
      ->delete();
    DB::table('rel_usuarios_perfiles')
      ->where('rel_usuario_perfil_id', '1')
      ->delete();

    // Eliminar el perfil sys_perfiles
    DB::table('sys_perfiles')
      ->where('perfil_id', '2')
      ->delete();
    DB::table('sys_perfiles')
      ->where('perfil_id', '1')
      ->delete();

    // Eliminar la relación usuario-sucursal
    DB::table('rel_usuarios_sucursales')
      ->where('rel_usuario_sucursal_id', '2')
      ->delete();
    DB::table('rel_usuarios_sucursales')
      ->where('rel_usuario_sucursal_id', '1')
      ->delete();

    // Eliminar la sucursal cat_sucursales
    DB::table('cat_sucursales')
      ->where('sucursal_id', '2')
      ->delete();
    DB::table('cat_sucursales')
      ->where('sucursal_id', '1')
      ->delete();

    // Eliminar el usuario sys_usuarios
    DB::table('sys_usuarios')
      ->where('usuario_id', '2')
      ->delete();
    DB::table('sys_usuarios')
      ->where('usuario_id', '1')
      ->delete();
  }
};
