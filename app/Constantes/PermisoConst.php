<?php

namespace App\Constantes;

class PermisoConst
{
  const LISTADO_PERMISOS = [
    // Usuarios
    'usuarios.agregar' => 'artegrafico.configuracion.sistema.usuarios.agregar',
    'usuarios.editar' => 'artegrafico.configuracion.sistema.usuarios.editar',
    'usuarios.eliminar' => 'artegrafico.configuracion.sistema.usuarios.eliminar',
    'usuarios.editarPassword' => 'artegrafico.configuracion.sistema.usuarios.cambiarContrasena',
    'usuarios.editarRelacionSucursales' => 'artegrafico.configuracion.sistema.usuarios.editarRelacionSucursales',
    'usuarios.editarImagen' => 'artegrafico.configuracion.sistema.usuarios.editar.imagen',
    // Perfiles
    'usuarios.agregarPerfil' => 'artegrafico.configuracion.sistema.perfiles.agregar',
    'usuarios.editarPerfil' => 'artegrafico.configuracion.sistema.perfiles.editar',
    'usuarios.eliminarPerfil' => 'artegrafico.configuracion.sistema.perfiles.eliminar',
    'usuarios.editarPermisosPerfil' => 'artegrafico.configuracion.sistema.perfiles.editarPermisos',
    // Sucursales
    'sucursales.agregar' => 'artegrafico.configuracion.sistema.sucursales.agregar',
    'sucursales.editar' => 'artegrafico.configuracion.sistema.sucursales.editar',
    'sucursales.eliminar' => 'artegrafico.configuracion.sistema.sucursales.eliminar',
    // Universidades
    'universidades.agregar' => 'artegrafico.configuracion.contratos.universidades.agregar',
    'universidades.editar' => 'artegrafico.configuracion.contratos.universidades.editar',
    'universidades.eliminar' => 'artegrafico.configuracion.contratos.universidades.eliminar',
    // Escuelas
    'escuelas.agregar' => 'artegrafico.configuracion.contratos.escuelas.agregar',
    'escuelas.editar' => 'artegrafico.configuracion.contratos.escuelas.editar',
    'escuelas.eliminar' => 'artegrafico.configuracion.contratos.escuelas.eliminar',
    // Carreras
    'carreras.agregar' => 'artegrafico.configuracion.contratos.carreras.agregar',
    'carreras.editar' => 'artegrafico.configuracion.contratos.carreras.editar',
    'carreras.eliminar' => 'artegrafico.configuracion.contratos.carreras.eliminar',
    // Grupos
    'grupos.agregar' => 'artegrafico.configuracion.contratos.grupos.agregar',
    'grupos.editar' => 'artegrafico.configuracion.contratos.grupos.editar',
    'grupos.eliminar' => 'artegrafico.configuracion.contratos.grupos.eliminar',
    // Contratos grupales
    'contrato-grupal.agregar' => 'artegrafico.contratos.grupales.agregar',
    'contrato-grupal.actualizarFechas' => 'artegrafico.contratos.grupales.editarFechas',
    'contrato-grupal.agregarPaquete' => 'artegrafico.contratos.grupales.agregar.paquete',
    'contrato-grupal.eliminarPaquete' => 'artegrafico.contratos.grupales.eliminar.paquete',
    'contrato-grupal.publicarContrato' => 'artegrafico.contratos.grupales.publicar',
    'contrato-grupal.editar' => 'artegrafico.contratos.grupales.editar',
    'contrato-grupal.eliminar' => 'artegrafico.contratos.grupales.eliminar',
    // Contratos individuales
    'contratoIndividual.agregarContratoIndividualDesdeAdmin' => 'artegrafico.contratos.individuales.agregar',
    'contratoIndividual.editarContratoIndividual' => 'artegrafico.contratos.individuales.editar',
    'contratoIndividual.eliminarContratoIndividual' => 'artegrafico.contratos.individuales.eliminar',
    // Lineas
    'lineas.agregar' => 'artegrafico.articulos.lineas.agregar',
    'lineas.editar' => 'artegrafico.articulos.lineas.editar',
    'lineas.eliminar' => 'artegrafico.articulos.lineas.eliminar',
    // Modelos
    'modelos.agregar' => 'artegrafico.articulos.modelos.agregar',
    'modelos.editar' => 'artegrafico.articulos.modelos.editar',
    'modelos.eliminar' => 'artegrafico.articulos.modelos.eliminar',
    'modelos.editarImagen' => 'artegrafico.articulos.modelos.editar.imagen',
    // Artículos paquete
    'articulosPaquete.agregar' => 'artegrafico.articulos.paquete.agregar',
    'articulosPaquete.editar' => 'artegrafico.articulos.paquete.editar',
    'articulosPaquete.eliminar' => 'artegrafico.articulos.paquete.eliminar',
    'articulosPaquete.editarImagen' => 'artegrafico.articulos.paquete.editar.imagen',
    // Artículos parametros
    'parametros.agregar' => 'artegrafico.articulos.parametros.agregar',
    'parametros.editar' => 'artegrafico.articulos.parametros.editar',
    'parametros.eliminar' => 'artegrafico.articulos.parametros.eliminar',
    // Artículos
    'articulos.agregar' => 'artegrafico.articulos.agregar',
    'articulos.editar' => 'artegrafico.articulos.editar',
    'articulos.eliminar' => 'artegrafico.articulos.eliminar',
    'articulos.editarImagen' => 'artegrafico.articulos.editar.imagen',
    'articulos.clonar' => 'artegrafico.articulos.clonar',
    // Plantillas
    'plantillas.editar' => 'artegrafico.articulos.plantillas.editar',
    // Combinaciones
    'combinaciones.agregar' => 'artegrafico.articulos.combinaciones.agregar',
    'combinaciones.editar' => 'artegrafico.articulos.combinaciones.editar',
    'combinaciones.eliminar' => 'artegrafico.articulos.combinaciones.eliminar',
    'combinaciones.editarImagen' => 'artegrafico.articulos.combinaciones.editar.imagen',
    // Paquetes
    'paquetes.agregar' => 'artegrafico.paquetes.agregar',
    'paquetes.editar' => 'artegrafico.paquetes.editar',
    'paquetes.editarImagen' => 'artegrafico.paquetes.editar.imagen',
    'paquetes.eliminar' => 'artegrafico.paquetes.eliminar',
    'paquetes.eliminarLinea' => 'artegrafico.paquetes.eliminar.linea',
    // Agradecimientos
    'agradecimientos.agregar' => 'artegrafico.articulos.agradecimientos.agregar',
    'agradecimientos.editar' => 'artegrafico.articulos.agradecimientos.editar',
    'agradecimientos.eliminar' => 'artegrafico.articulos.agradecimientos.eliminar',
    // Pagos
    'pagos.agregar' => 'artegrafico.contratos.individuales.pagos.agregar',
    'pagos.cancelar' => 'artegrafico.contratos.individuales.pagos.cancelar',
    'pagos.devolucionEconomica' => 'artegrafico.contratos.individuales.devoluciones.agregar',
    // Ajustes economicos
    'contratoIndividual.ajusteEconomicoPaquete' => 'artegrafico.contratos.individuales.ajuste.economico',
    'contratoIndividual.ajusteEconomicoArticulo' => 'artegrafico.contratos.individuales.ajuste.economico',
  ];
}
