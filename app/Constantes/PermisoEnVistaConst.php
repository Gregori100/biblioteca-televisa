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
  ];
}
