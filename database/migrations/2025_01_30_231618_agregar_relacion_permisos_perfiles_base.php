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
      [
        'perfil_id'         => 1,
        'permiso_id'        => 13,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 14,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 15,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 16,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 17,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 18,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 19,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 20,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 21,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 22,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 23,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 24,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 25,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 26,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 27,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 28,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 29,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 30,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 31,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 32,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 33,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 34,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 35,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 36,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 37,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 38,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 39,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 40,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 41,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 42,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 43,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 44,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 45,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 46,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 47,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 48,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 49,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 50,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 51,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 52,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 53,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 54,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 55,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 56,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 57,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 58,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 59,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 60,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 61,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 62,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 63,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 64,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 65,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 66,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 1,
        'permiso_id'        => 67,
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
      [
        'perfil_id'         => 2,
        'permiso_id'        => 13,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 14,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 15,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 16,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 17,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 18,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 19,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 20,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 21,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 22,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 23,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 24,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 25,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 26,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 27,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 28,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 29,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 30,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 31,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 32,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 33,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 34,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 35,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 36,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 37,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 38,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 39,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 40,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 41,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 42,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 43,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 44,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 45,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 46,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 47,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 48,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 49,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 50,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 51,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 52,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 53,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 54,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 55,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 56,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 57,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 58,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 59,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 60,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 61,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 62,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 63,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 64,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 65,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 66,
        'registro_autor_id' => 1,
        'registro_fecha'    => FechaUtils::fechaActual(),
      ],
      [
        'perfil_id'         => 2,
        'permiso_id'        => 67,
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
