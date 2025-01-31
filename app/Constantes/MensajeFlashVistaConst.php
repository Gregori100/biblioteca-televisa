<?php

namespace App\Constantes;

class MensajeFlashVistaConst
{
  const MENSAJE_FLASH_VISTA = [
    // Usuarios
    'usuarios' => [
      'exito' => [
        'agregar'             => 'Usuario creado correctamente',
        'editar'              => 'Usuario editado correctamente',
        'eliminar'            => 'Usuario eliminado correctamente',
        'editarContrena'      => 'Contraseña de usuario editada correctamente',
        'eliminarRelSucursal' => 'Relación con sucursales editada correctamente',
        'editarImagen'        => 'Imagen de usuario editada correctamente',
      ],
    ],
    // Perfiles
    'perfiles' => [
      'exito' => [
        'agregar'  => 'Perfil creado correctamente',
        'editar'   => 'Perfil editado correctamente',
        'eliminar' => 'Perfil eliminado correctamente',
      ],
    ],
    // Universidades
    'universidades' => [
      'exito' => [
        'agregarU'  => 'Universidad creada correctamente',
        'editarU'   => 'Universidad editada correctamente',
        'eliminarU' => 'Universidad eliminada correctamente',
        'agregarE'  => 'Escuela creada correctamente',
        'editarE'   => 'Escuela editada correctamente',
        'eliminarE' => 'Escuela eliminada correctamente',
        'agregarC'  => 'Carrera creada correctamente',
        'editarC'   => 'Carrera editada correctamente',
        'eliminarC' => 'Carrera eliminada correctamente',
        'agregarG'  => 'Grupo creado correctamente',
        'editarG'   => 'Grupo editado correctamente',
        'eliminarG' => 'Grupo eliminado correctamente',
      ],
    ],
    // Sucursales
    'sucursales' => [
      'exito' => [
        'agregar'  => 'Sucursal creada correctamente',
        'editar'   => 'Sucursal editada correctamente',
        'eliminar' => 'Sucursal eliminada correctamente',
      ],
    ],
    // Lineas
    'lineas' => [
      'exito' => [
        'agregar'  => 'Linea creada correctamente',
        'editar'   => 'Linea editada correctamente',
        'eliminar' => 'Linea eliminada correctamente',
      ],
    ],
    // Modelos
    'modelos' => [
      'exito' => [
        'agregar'  => 'Modelo creado correctamente',
        'editar'   => 'Modelo editado correctamente',
        'eliminar' => 'Modelo eliminado correctamente',
        'editar-imagen' => 'Imagen de modelo editada correctamente',
      ],
    ],
    // Parametros
    'parametros' => [
      'exito' => [
        'agregar'  => 'Parámetro creado correctamente',
        'editar'   => 'Parámetro editado correctamente',
        'eliminar' => 'Parámetro eliminado correctamente',
      ],
    ],
    // Articulos paquete
    'articulosPaquete' => [
      'exito' => [
        'agregar'  => 'Artículo de paquete creado correctamente',
        'editar'   => 'Artículo de paquete editado correctamente',
        'eliminar' => 'Artículo de paquete eliminado correctamente',
        'editar-imagen' => 'Imagen de artículo paquete editada correctamente',
      ],
    ],
    // Articulos
    'articulos' => [
      'exito' => [
        'agregar'  => 'Artículo creado correctamente',
        'editar'   => 'Artículo editado correctamente',
        'eliminar' => 'Artículo eliminado correctamente',
        'editar-imagen' => 'Imagen de articulo editada correctamente',
        'agregar-plantilla'  => 'Plantilla agregada correctamente',
        'editar-plantilla'   => 'Plantilla editada correctamente',
        'clonar'  => 'Artículo clonado correctamente',
        'agregar-combinacion'  => 'Combinación agregada correctamente',
        'editar-combinacion'   => 'Combinación editada correctamente',
        'eliminar-combinacion' => 'Combinación eliminada correctamente',
        'editar-imagen-combinacion' => 'Imagen de combinación editada correctamente',
      ],
    ],
    // Contratos grupales
    'contratosGrupales' => [
      'exito' => [
        'agregar'       => 'Contrato grupal creado correctamente',
        'editar-fechas' => 'Fechas del contrato grupal editadas correctamente',
        'agregar-paquete' => 'Paquete agregado al contrato grupal correctamente',
        'eliminar-paquete' => 'Paquete eliminado del contrato grupal correctamente',
        'publicar-contrato' => 'Contrato grupal publicado correctamente',
        'publicar-contrato-sms' => 'Contrato grupal publicado, pero no se pudo enviar el sms',
        'publicar-contrato-correo' => 'Contrato grupal publicado, pero no se pudo enviar el correo electrónico',
        'publicar-contrato-sms-correo' => 'Contrato grupal publicado, pero no se envio notificacion',
        'editar-contrato' => 'Contrato grupal editado correctamente',
        'eliminar-contrato' => 'Contrato grupal eliminado correctamente',
      ],
    ],
    // Contratos individuales
    'contratosIndividuales' => [
      'exito' => [
        'agregar'  => 'Contrato individual creado correctamente',
        'editar'   => 'Contrato individual editado correctamente',
        'eliminar' => 'Contrato individual eliminado correctamente',
        'eliminar-paquete' => 'Paquete eliminado correctamente.',
        'agregar-paquete' => 'Paquete agregado correctamente.',
        'editar-paquete' => 'Paquete editado correctamente.',
        'config-articulo' => 'Articulo configurado correctamente.',
        'finalizar-pedido' => 'Pedido finalizado correctamente.',
        'agregar-pago' => 'Pago realizado correctamente.',
        'abrir-contrato' => 'Contrato abierto correctamente.',
        'agregar-articulo' => 'Articulo adicional agregado correctamente.',
        'eliminar-articulo' => 'Articulo adicional eliminado correctamente.',
        'seleccionar-modelo' => 'Modelo de artículo seleccionado correctamente.',
        'personalizar-articulo' => 'Artículo personalizado correctamente.',
        'ajuste-economico' => 'Ajuste económico realizado correctamente.',
      ],
    ],
    // Paquetes
    'paquetes' => [
      'exito' => [
        'agregar'  => 'Paquete creado correctamente',
        'editar'   => 'Paquete editado correctamente',
        'eliminar' => 'Paquete eliminado correctamente',
        'editar-imagen' => 'Imagen de paquete editada correctamente',
        'eliminar-linea' => 'Linea eliminada del paquete correctamente',
      ],
    ],
    // Agradecimientos
    'agradecimientos' => [
      'exito' => [
        'agregar'  => 'Agradecimiento creado correctamente',
        'editar'   => 'Agradecimiento editado correctamente',
        'eliminar' => 'Agradecimiento eliminado correctamente',
      ],
    ],
    // Clientes
    'clientes' => [
      'exito' => [
        'editar' => 'Datos editados correctamente, por favor vuelve a iniciar sesión para reflejar cambios',
        'agregar-paquete' => 'Paquete agregado correctamente.',
        'eliminar-paquete' => 'Paquete eliminado correctamente.',
        'eliminar-articulo' => 'Articulo adicional eliminado correctamente.',
        'config-articulo' => 'Articulo configurado correctamente.',
        'finalizar-pedido' => 'Pedido finalizado correctamente.',
        'agregar-articulo' => 'Articulo adicional agregado correctamente.',
        'editar-pedido' => 'Contrato abierto correctamente.',
      ],
    ],
    // Pagos
    'pagos' => [
      'exito' => [
        'eliminar' => 'Pago cancelado correctamente',
      ],
    ],
    // Comprobantes
    'comprobantes' => [
      'exito' => [
        'agregar' => 'Comprobante de pago agregado correctamente',
        'rechazar' => 'Comprobante de pago rechazado correctamente',
        'autorizar' => 'Comprobante de pago autorizado correctamente',
      ],
    ],
    // Libros
    'libros' => [
      'exito' => [
        'agregar' => 'Libro agregado correctamente',
        'editar' => 'Libro editado correctamente',
        'eliminar' => 'Libro eliminado correctamente',
        'ocupar' => 'Libro ocupado correctamente',
      ],
    ],
  ];
}
