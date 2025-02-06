<?php

namespace App\Constantes;

class PermisoConst
{
  const LISTADO_PERMISOS = [
    // Libros
    'libros.agregar' => 'biblioteca.libros.agregar',
    'libros.editar' => 'biblioteca.libros.editar',
    'libros.eliminar' => 'biblioteca.libros.eliminar',
    // 'libros.ocupar' => 'biblioteca.libros.ocupar',

    // Usuarios
    'usuarios.agregar' => 'biblioteca.usuarios.agregar',
    'usuarios.editar' => 'biblioteca.usuarios.editar',
    'usuarios.eliminar' => 'biblioteca.usuarios.eliminar',
    'usuarios.editarPassword' => 'biblioteca.usuarios.cambiarContrasena',

    // Perfiles
    'usuarios.agregarPerfil' => 'biblioteca.perfiles.agregar',
    'usuarios.editarPerfil' => 'biblioteca.perfiles.editar',
    'usuarios.eliminarPerfil' => 'biblioteca.perfiles.eliminar',
    'usuarios.editarPermisosPerfil' => 'biblioteca.perfiles.editarPermisos',
  ];
}
