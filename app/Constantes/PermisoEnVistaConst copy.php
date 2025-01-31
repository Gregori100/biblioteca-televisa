<?php

namespace App\Constantes;

class PermisoEnVistaConst
{
  const PERMISOS_EN_VISTA = [
    // Usuarios
    'usuarios.gestor' => [
      'agregar'  => 'artegrafico.configuracion.sistema.usuarios.agregar',
      'editar'   => 'artegrafico.configuracion.sistema.usuarios.editar',
      'eliminar' => 'artegrafico.configuracion.sistema.usuarios.eliminar',
    ],
    'usuarios.detalle' => [
      'editar'              => 'artegrafico.configuracion.sistema.usuarios.editar',
      'eliminar'            => 'artegrafico.configuracion.sistema.usuarios.eliminar',
      'editarPassword'      => 'artegrafico.configuracion.sistema.usuarios.cambiarContrasena',
      'editarRelSucursales' => 'artegrafico.configuracion.sistema.usuarios.editarRelacionSucursales',
      'editarImagen'        => 'artegrafico.configuracion.sistema.usuarios.editar.imagen',
    ],
    // Perfiles
    'usuarios.perfilesGestor' => [
      'agregar'  => 'artegrafico.configuracion.sistema.perfiles.agregar',
      'eliminar' => 'artegrafico.configuracion.sistema.perfiles.eliminar',
    ],
    'usuarios.detallePerfil' => [
      'editar'         => 'artegrafico.configuracion.sistema.perfiles.editar',
      'eliminar'       => 'artegrafico.configuracion.sistema.perfiles.eliminar',
      'editarPermisos' => 'artegrafico.configuracion.sistema.perfiles.editarPermisos',
    ],
    'usuarios.editarPermisos' => [
      'editarPermisos' => 'artegrafico.configuracion.sistema.perfiles.editarPermisos',
    ],
    // Universidades
    'universidades.gestor' => [
      'agregarUniversidad' => 'artegrafico.configuracion.contratos.universidades.agregar',
      'editarUniversidad' => 'artegrafico.configuracion.contratos.universidades.editar',
      'eliminarUniversidad' => 'artegrafico.configuracion.contratos.universidades.eliminar',
      // Escuelas
      'agregarEscuela' => 'artegrafico.configuracion.contratos.escuelas.agregar',
      'editarEscuela' => 'artegrafico.configuracion.contratos.escuelas.editar',
      'eliminarEscuela' => 'artegrafico.configuracion.contratos.escuelas.eliminar',
      // Carreras
      'agregarCarrera' => 'artegrafico.configuracion.contratos.carreras.agregar',
      'editarCarrera' => 'artegrafico.configuracion.contratos.carreras.editar',
      'eliminarCarrera' => 'artegrafico.configuracion.contratos.carreras.eliminar',
      // Grupos
      'agregarGrupo' => 'artegrafico.configuracion.contratos.grupos.agregar',
      'editarGrupo' => 'artegrafico.configuracion.contratos.grupos.editar',
      'eliminarGrupo' => 'artegrafico.configuracion.contratos.grupos.eliminar',
    ],
    // Sucursales
    'sucursales.gestor' => [
      'agregar'  => 'artegrafico.configuracion.sistema.sucursales.agregar',
      'editar'   => 'artegrafico.configuracion.sistema.sucursales.editar',
      'eliminar' => 'artegrafico.configuracion.sistema.sucursales.eliminar',
    ],
    'sucursales.detalle' => [
      'editar'   => 'artegrafico.configuracion.sistema.sucursales.editar',
      'eliminar' => 'artegrafico.configuracion.sistema.sucursales.eliminar',
    ],
    // Lineas
    'lineas.gestor' => [
      'agregar'  => 'artegrafico.articulos.lineas.agregar',
      'editar'   => 'artegrafico.articulos.lineas.editar',
      'eliminar' => 'artegrafico.articulos.lineas.eliminar',
    ],
    'lineas.detalle' => [
      'editar'   => 'artegrafico.articulos.lineas.editar',
      'eliminar' => 'artegrafico.articulos.lineas.eliminar',
    ],
    // Modelos
    'modelos.gestor' => [
      'agregar'  => 'artegrafico.articulos.modelos.agregar',
      'editar'   => 'artegrafico.articulos.modelos.editar',
      'eliminar' => 'artegrafico.articulos.modelos.eliminar',
    ],
    'modelos.detalle' => [
      'editar'   => 'artegrafico.articulos.modelos.editar',
      'eliminar' => 'artegrafico.articulos.modelos.eliminar',
      'editarImagen' => 'artegrafico.articulos.modelos.editar.imagen',
    ],
    // Parametros
    'parametros.gestor' => [
      'agregar'  => 'artegrafico.articulos.parametros.agregar',
      'editar'   => 'artegrafico.articulos.parametros.editar',
      'eliminar' => 'artegrafico.articulos.parametros.eliminar',
    ],
    // Artículos de paquete
    'articulosPaquete.gestor' => [
      'agregar'  => 'artegrafico.articulos.paquete.agregar',
      'editar'   => 'artegrafico.articulos.paquete.editar',
      'eliminar' => 'artegrafico.articulos.paquete.eliminar',
    ],
    'articulosPaquete.detalle' => [
      'editar'   => 'artegrafico.articulos.paquete.editar',
      'eliminar' => 'artegrafico.articulos.paquete.eliminar',
      'editarImagen' => 'artegrafico.articulos.paquete.editar.imagen',
    ],
    // Articulos
    'articulos.gestor' => [
      "agregar"  => "artegrafico.articulos.agregar",
      "editar"   => "artegrafico.articulos.editar",
      "eliminar" => "artegrafico.articulos.eliminar",
    ],
    'articulos.detalle' => [
      "editar"   => "artegrafico.articulos.editar",
      "eliminar" => "artegrafico.articulos.eliminar",
      'editarImagen' => 'artegrafico.articulos.editar.imagen',
      'agregarPlantilla' => 'artegrafico.articulos.plantillas.agregar',
      'editarPlantilla' => 'artegrafico.articulos.plantillas.editar',
      'eliminarPlantilla' => 'artegrafico.articulos.plantillas.eliminar',
      "clonar" => "artegrafico.articulos.clonar",
      'agregarCombinacion' => 'artegrafico.articulos.combinaciones.agregar',
      'editarCombinacion' => 'artegrafico.articulos.combinaciones.editar',
      'eliminarCombinacion' => 'artegrafico.articulos.combinaciones.eliminar',
      'editarImagenCombinacion' => 'artegrafico.articulos.combinaciones.editar.imagen',
    ],
    'articulos.vistaEditar' => [
      "editar"   => "artegrafico.articulos.editar",
    ],
    // Contratos grupales
    'contratoGrupal.gestor' => [
      'agregar'  => 'artegrafico.contratos.grupales.agregar',
      'publicar' => 'artegrafico.contratos.grupales.publicar',
      'editar'   => 'artegrafico.contratos.grupales.editar',
      'eliminar' => 'artegrafico.contratos.grupales.eliminar',
    ],
    'contratoGrupal.detalle' => [
      'editarFechas' => 'artegrafico.contratos.grupales.editarFechas',
      'agregarPaquete' => 'artegrafico.contratos.grupales.agregar.paquete',
      'eliminarPaquete' => 'artegrafico.contratos.grupales.eliminar.paquete',
      'publicar' => 'artegrafico.contratos.grupales.publicar',
      'editar' => 'artegrafico.contratos.grupales.editar',
      'eliminar' => 'artegrafico.contratos.grupales.eliminar',
    ],
    // Contratos individuales
    'contratoIndividual.gestor' => [
      'agregar' => 'artegrafico.contratos.individuales.agregar',
    ],
    'contratoIndividual.detalle' => [
      'editar'   => 'artegrafico.contratos.individuales.editar',
      'eliminar' => 'artegrafico.contratos.individuales.eliminar',
      'pagosAgregar' => 'artegrafico.contratos.individuales.pagos.agregar',
      'ajusteEconomico' => 'artegrafico.contratos.individuales.ajuste.economico',
    ],
    'contratoIndividual.pagoContrato' => [
      'pagosAgregar' => 'artegrafico.contratos.individuales.pagos.agregar',
    ],
    // Paquetes
    'paquetes.gestor' => [
      "agregar"  => "artegrafico.paquetes.agregar",
      "editar"   => "artegrafico.paquetes.editar",
      "eliminar" => "artegrafico.paquetes.eliminar",
    ],
    'paquetes.detalle' => [
      "editar"   => "artegrafico.paquetes.editar",
      "eliminar" => "artegrafico.paquetes.eliminar",
      "editarImagen" => "artegrafico.paquetes.editar.imagen",
      "eliminarLinea" => "artegrafico.paquetes.eliminar.linea",
    ],
    'paquetes.vistaEditar' => [
      "editar"   => "artegrafico.paquetes.editar",
    ],
    // Agradecimientos
    'agradecimientos.gestor' => [
      "agregar"  => "artegrafico.articulos.agradecimientos.agregar",
      "editar"   => "artegrafico.articulos.agradecimientos.editar",
      "eliminar" => "artegrafico.articulos.agradecimientos.eliminar",
    ],
    // Pagos
    'pagos.detalle' => [
      'pagosCancelar' => 'artegrafico.contratos.individuales.pagos.cancelar',
    ],
    'pagos.gestor' => [
      'pagosCancelar' => 'artegrafico.contratos.individuales.pagos.cancelar',
    ],
  ];
}
