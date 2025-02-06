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
    DB::table('rel_perfiles_permisos')->insert([
      // Perfil master
      [
        'perfil_id'         => 1,
        'permiso_id'        => 1,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 2,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 3,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 4,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 5,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 6,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 7,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 8,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 9,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 10,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 11,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 12,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      // Perfil administrador
      [
        'perfil_id'         => 2,
        'permiso_id'        => 1,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 2,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 3,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 4,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 5,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 6,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 7,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 8,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 9,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 10,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 11,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 12,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    DB::table('rel_perfiles_permisos')->whereIn('perfil_id', [1, 2])->delete();
  }
};
