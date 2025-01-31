<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    // cat_libros
    Schema::create('cat_libros', function (Blueprint $table) {
      $table->id('libro_id');
      $table->unsignedBigInteger('folio');
      $table->string('nombre', 240);
      $table->string('autor_nombre', 240)->nullable();
      $table->string('editorial_nombre', 240)->nullable();
      $table->smallInteger('numero_paginas')->unsigned()->default(0)->nullable();
      $table->string('genero', 100)->nullable();
      $table->string('idioma', 100)->nullable();
      $table->string('isbn')->nullable();
      $table->timestamp('salida_fecha')->nullable();
      $table->timestamp('regreso_fecha')->nullable();
      $table->text('observaciones')->nullable();
      $table->text('motivo_eliminacion')->nullable();
      $table->smallInteger('status')
        ->unsigned()
        ->default(200)
        ->comment('Status: 200(Activo), 300(Eliminado)');
      $table->string('status_disponibilidad')
        ->nullable()
        ->default("NO_APLICA")
        ->comment('Status de disponibilidad: DISPONIBLE, OCUPADO, RETIRADO');
      $table->unsignedBigInteger('registro_autor_id')->nullable();
      $table->timestamp('registro_fecha')->useCurrent();
      $table->unsignedBigInteger('actualizacion_autor_id')->nullable();
      $table->timestamp('actualizacion_fecha')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('cat_libros');
  }
};
