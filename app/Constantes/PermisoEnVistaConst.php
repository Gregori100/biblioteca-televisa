<?php

namespace App\Constantes;

class PermisoEnVistaConst
{
  const PERMISOS_EN_VISTA = [
    // Libros
    'libros.gestor' => [
      'agregar'  => 'biblioteca.libros.agregar',
      'editar'   => 'biblioteca.libros.editar',
      'eliminar' => 'biblioteca.libros.eliminar',
      'ocupar' => 'biblioteca.libros.eliminar',
    ],

    // Usuarios
    'usuarios.gestor' => [
      'agregar'  => 'biblioteca.usuarios.agregar',
      'editar'   => 'biblioteca.usuarios.editar',
      'eliminar' => 'biblioteca.usuarios.eliminar',
    ],
    'usuarios.detalle' => [
      'editar'         => 'biblioteca.usuarios.editar',
      'eliminar'       => 'biblioteca.usuarios.eliminar',
      'editarPassword' => 'biblioteca.usuarios.cambiarContrasena',
    ],

    // Perfiles
    'usuarios.perfilesGestor' => [
      'agregar'  => 'biblioteca.perfiles.agregar',
      'eliminar' => 'biblioteca.perfiles.eliminar',
    ],
    'usuarios.detallePerfil' => [
      'editar'         => 'biblioteca.perfiles.editar',
      'eliminar'       => 'biblioteca.perfiles.eliminar',
      'editarPermisos' => 'biblioteca.perfiles.editarPermisos',
    ],
    'usuarios.editarPermisos' => [
      'editarPermisos' => 'biblioteca.perfiles.editarPermisos',
    ],
  ];
}
