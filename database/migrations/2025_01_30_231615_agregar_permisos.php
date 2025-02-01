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
    ])->delete();
  }
};
