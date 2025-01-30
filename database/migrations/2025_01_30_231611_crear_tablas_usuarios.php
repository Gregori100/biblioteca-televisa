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
    // sys_usuarios
    Schema::create('sys_usuarios', function (Blueprint $table) {
      $table->id('usuario_id');
      $table->string('usuario', 20);
      $table->string('password', 128);
      $table->string('nombre_completo', 100);
      $table->string('nombre_corto', 20)->nullable();
      $table->string('email', 200)->nullable();
      $table->string('telefono', 25)->nullable();
      $table->timestamp('fecha_ultimo_acceso')->nullable();
      $table->smallInteger('status')->unsigned()->default(200);
      $table->boolean('global')->default(false);
      $table->unsignedBigInteger('registro_autor_id')->nullable();
      $table->timestamp('registro_fecha')->nullable();
      $table->unsignedBigInteger('actualizacion_autor_id')->nullable();
      $table->timestamp('actualizacion_fecha')->nullable();
    });

    // cat_sucursales
    Schema::create('cat_sucursales', function (Blueprint $table) {
      $table->id('sucursal_id');
      $table->string('clave', 5);
      $table->string('titulo', 45);
      $table->string('cp', 10);
      $table->string('direccion_calle', 500);
      $table->string('direccion_colonia', 500);
      $table->string('telefono', 20)->nullable();
      $table->string('zona_horaria', 20);
      $table->text('documento_encabezado')->nullable();
      $table->text('documento_pie_pagina')->nullable();
      $table->smallInteger('status')->unsigned()->default(200);
      $table->text('metadatos')->nullable();
      $table->boolean('default')->default(false);
      $table->unsignedBigInteger('registro_autor_id');
      $table->timestamp('registro_fecha');
      $table->unsignedBigInteger('actualizacion_autor_id')->nullable();
      $table->timestamp('actualizacion_fecha')->nullable();
    });

    // rel_usuarios_sucursales
    Schema::create('rel_usuarios_sucursales', function (Blueprint $table) {
      $table->id('rel_usuario_sucursal_id');
      $table->unsignedBigInteger('usuario_id');
      $table->unsignedBigInteger('sucursal_id');
      $table->smallInteger('status')->unsigned()->default(200);
      $table->unsignedBigInteger('registro_autor_id');
      $table->timestamp('registro_fecha');
      $table->unsignedBigInteger('actualizacion_autor_id')->nullable();
      $table->timestamp('actualizacion_fecha')->nullable();

      $table->foreign('usuario_id')->references('usuario_id')->on('sys_usuarios');
      $table->foreign('sucursal_id')->references('sucursal_id')->on('cat_sucursales');
    });

    // sys_perfiles
    Schema::create('sys_perfiles', function (Blueprint $table) {
      $table->id('perfil_id');
      $table->string('clave', 20);
      $table->string('titulo', 45);
      $table->string('descripcion', 250)->nullable();
      $table->smallInteger('status')->unsigned()->default(200);
      $table->boolean('global')->default(false);
      $table->unsignedBigInteger('registro_autor_id');
      $table->timestamp('registro_fecha');
      $table->unsignedBigInteger('actualizacion_autor_id')->nullable();
      $table->timestamp('actualizacion_fecha')->nullable();
    });

    // rel_usuarios_perfiles
    Schema::create('rel_usuarios_perfiles', function (Blueprint $table) {
      $table->id('rel_usuario_perfil_id');
      $table->unsignedBigInteger('perfil_id');
      $table->unsignedBigInteger('usuario_id');
      $table->smallInteger('status')->unsigned()->default(200);
      $table->unsignedBigInteger('registro_autor_id');
      $table->timestamp('registro_fecha');
      $table->unsignedBigInteger('actualizacion_autor_id')->nullable();
      $table->timestamp('actualizacion_fecha')->nullable();

      $table->foreign('perfil_id')->references('perfil_id')->on('sys_perfiles');
      $table->foreign('usuario_id')->references('usuario_id')->on('sys_usuarios');
    });

    // sys_permisos
    Schema::create('sys_permisos', function (Blueprint $table) {
      $table->id('permiso_id');
      $table->string('codigo', 150);
      $table->string('titulo', 75);
      $table->string('descripcion', 350);
      $table->string('seccion', 35);
      $table->unsignedBigInteger('orden_seccion');
    });

    // rel_perfiles_permisos
    Schema::create('rel_perfiles_permisos', function (Blueprint $table) {
      $table->id('rel_permiso_perfil_id');
      $table->unsignedBigInteger('perfil_id');
      $table->unsignedBigInteger('permiso_id');
      $table->unsignedBigInteger('registro_autor_id');
      $table->timestamp('registro_fecha');

      $table->foreign('perfil_id')->references('perfil_id')->on('sys_perfiles');
    });

    // cat_folios
    Schema::create('cat_folios', function (Blueprint $table) {
      $table->string('key', 500);
      $table->unsignedBigInteger('folio');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('cat_folios');
    Schema::dropIfExists('rel_usuarios_perfiles');
    Schema::dropIfExists('rel_usuarios_sucursales');
    Schema::dropIfExists('rel_perfiles_permisos');
    Schema::dropIfExists('sys_permisos');
    Schema::dropIfExists('sys_perfiles');
    Schema::dropIfExists('cat_sucursales');
    Schema::dropIfExists('sys_usuarios');
  }
};
