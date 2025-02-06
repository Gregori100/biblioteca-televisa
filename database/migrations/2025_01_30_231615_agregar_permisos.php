<?php

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
    DB::table('sys_permisos')->insert([
      // Libros
      [
        'permiso_id'    => 1,
        'codigo'        => 'biblioteca.libros.agregar',
        'titulo'        => 'Alta de libros.',
        'descripcion'   => 'Permite dar de alta libros.',
        'seccion'       => 'Libros',
        'orden_seccion' => 1,
      ],
      [
        'permiso_id'    => 2,
        'codigo'        => 'biblioteca.libros.editar',
        'titulo'        => 'Edición de libros.',
        'descripcion'   => 'Permite editar libros.',
        'seccion'       => 'Libros',
        'orden_seccion' => 1,
      ],
      [
        'permiso_id'    => 3,
        'codigo'        => 'biblioteca.libros.eliminar',
        'titulo'        => 'Eliminación de libros.',
        'descripcion'   => 'Permite eliminar libros.',
        'seccion'       => 'Libros',
        'orden_seccion' => 1,
      ],
      [
        'permiso_id'    => 4,
        'codigo'        => 'biblioteca.libros.ocupar',
        'titulo'        => 'Ocupación de libros.',
        'descripcion'   => 'Permite ocupar libros.',
        'seccion'       => 'Libros',
        'orden_seccion' => 1,
      ],

      // Perfiles y Usuarios
      [
        'permiso_id'    => 5,
        'codigo'        => 'biblioteca.usuarios.agregar',
        'titulo'        => 'Alta de usuario.',
        'descripcion'   => 'Permite dar de alta usuarios.',
        'seccion'       => 'Usuarios',
        'orden_seccion' => 2,
      ],
      [
        'permiso_id'    => 6,
        'codigo'        => 'biblioteca.usuarios.editar',
        'titulo'        => 'Edición de usuario.',
        'descripcion'   => 'Permite editar usuarios.',
        'seccion'       => 'Usuarios',
        'orden_seccion' => 2,
      ],
      [
        'permiso_id'    => 7,
        'codigo'        => 'biblioteca.usuarios.eliminar',
        'titulo'        => 'Eliminación de usuario.',
        'descripcion'   => 'Permite eliminar usuarios.',
        'seccion'       => 'Usuarios',
        'orden_seccion' => 2,
      ],
      [
        'permiso_id'    => 8,
        'codigo'        => 'biblioteca.usuarios.cambiarContrasena',
        'titulo'        => 'Edición contraseña de usuario.',
        'descripcion'   => 'Permite eliminar usuarios.',
        'seccion'       => 'Usuarios',
        'orden_seccion' => 2,
      ],
      [
        'permiso_id'    => 9,
        'codigo'        => 'biblioteca.perfiles.agregar',
        'titulo'        => 'Alta de perfiles.',
        'descripcion'   => 'Permite dar de alta perfiles de acceso.',
        'seccion'       => 'Perfiles',
        'orden_seccion' => 3,
      ],
      [
        'permiso_id'    => 10,
        'codigo'        => 'biblioteca.perfiles.editar',
        'titulo'        => 'Edición de perfiles.',
        'descripcion'   => 'Permite editar perfiles de acceso.',
        'seccion'       => 'Perfiles',
        'orden_seccion' => 3,
      ],
      [
        'permiso_id'    => 11,
        'codigo'        => 'biblioteca.perfiles.eliminar',
        'titulo'        => 'Eliminación de perfiles.',
        'descripcion'   => 'Permite eliminar perfiles de acceso.',
        'seccion'       => 'Perfiles',
        'orden_seccion' => 3,
      ],
      [
        'permiso_id'    => 12,
        'codigo'        => 'biblioteca.perfiles.editarPermisos',
        'titulo'        => 'Edición permisos de perfil.',
        'descripcion'   => 'Permite editar los permisos relacionados al perfil.',
        'seccion'       => 'Perfiles',
        'orden_seccion' => 3,
      ],
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    DB::table('sys_permisos')->whereIn('codigo', [
      'biblioteca.libros.agregar',
      'biblioteca.libros.editar',
      'biblioteca.libros.eliminar',
      'biblioteca.libros.ocupar',
      'biblioteca.usuarios.agregar',
      'biblioteca.usuarios.editar',
      'biblioteca.usuarios.eliminar',
      'biblioteca.usuarios.cambiarContrasena',
      'biblioteca.perfiles.agregar',
      'biblioteca.perfiles.editar',
      'biblioteca.perfiles.eliminar',
      'biblioteca.perfiles.editarPermisos',
    ])->delete();
  }
};
