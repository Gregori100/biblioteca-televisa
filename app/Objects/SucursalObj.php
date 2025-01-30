<?php

namespace App\Objects;

/*
|--------------------------------------------------------------------------
| Clase para crear objetos de sucursales
|--------------------------------------------------------------------------
*/

class SucursalObj
{
  private $sucursalId;
  private $clave;
  private $titulo;
  private $cp;
  private $direccionCalle;
  private $direccionColonia;
  private $telefono;
  private $zonaHoraria;
  private $documentoEncabezado;
  private $documentoPiePagina;
  private $status;
  private $metadatos;
  private $default;
  private $registroAutorId;
  private $registroFecha;
  private $actualizacionAutorId;
  private $actualizacionFecha;
  private $statusNombre;
  private $registroAutor;
  private $actualizacionAutor;
  private $zonaHorariaDescripcion;

  /****************************************/
  /**************** Getters ***************/
  /****************************************/
  public function getSucursalId()
  {
    return $this->sucursalId;
  }
  public function getClave()
  {
    return $this->clave;
  }
  public function getTitulo()
  {
    return $this->titulo;
  }
  public function getCp()
  {
    return $this->cp;
  }
  public function getDireccionCalle()
  {
    return $this->direccionCalle;
  }
  public function getDireccionColonia()
  {
    return $this->direccionColonia;
  }
  public function getTelefono()
  {
    return $this->telefono;
  }
  public function getZonaHoraria()
  {
    return $this->zonaHoraria;
  }
  public function getDocumentoEncabezado()
  {
    return $this->documentoEncabezado;
  }
  public function getDocumentoPiePagina()
  {
    return $this->documentoPiePagina;
  }
  public function getStatus()
  {
    return $this->status;
  }
  public function getMetadatos()
  {
    return $this->metadatos;
  }
  public function getDefault()
  {
    return $this->default;
  }
  public function getRegistroAutorId()
  {
    return $this->registroAutorId;
  }
  public function getRegistroFecha()
  {
    return $this->registroFecha;
  }
  public function getActualizacionAutorId()
  {
    return $this->actualizacionAutorId;
  }
  public function getActualizacionFecha()
  {
    return $this->actualizacionFecha;
  }
  public function getStatusNombre()
  {
    return $this->statusNombre;
  }
  public function getRegistroAutor()
  {
    return $this->registroAutor;
  }
  public function getActualizacionAutor()
  {
    return $this->actualizacionAutor;
  }
  public function getZonaHorariaDescripcion()
  {
    return $this->zonaHorariaDescripcion;
  }


  /****************************************/
  /**************** Setters ***************/
  /****************************************/
  public function setSucursalId($sucursalId)
  {
    $this->sucursalId = $sucursalId;
  }
  public function setClave($clave)
  {
    $this->clave = $clave;
  }
  public function setTitulo($titulo)
  {
    $this->titulo = $titulo;
  }
  public function setCp($cp)
  {
    $this->cp = $cp;
  }
  public function setDireccionCalle($direccionCalle)
  {
    $this->direccionCalle = $direccionCalle;
  }
  public function setDireccionColonia($direccionColonia)
  {
    $this->direccionColonia = $direccionColonia;
  }
  public function setTelefono($telefono)
  {
    $this->telefono = $telefono;
  }
  public function setZonaHoraria($zonaHoraria)
  {
    $this->zonaHoraria = $zonaHoraria;
  }
  public function setDocumentoEncabezado($documentoEncabezado)
  {
    $this->documentoEncabezado = $documentoEncabezado;
  }
  public function setDocumentoPiePagina($documentoPiePagina)
  {
    $this->documentoPiePagina = $documentoPiePagina;
  }
  public function setStatus($status)
  {
    $this->status = $status;
  }
  public function setMetadatos($metadatos)
  {
    $this->metadatos = $metadatos;
  }
  public function setDefault($default)
  {
    $this->default = $default;
  }
  public function setRegistroAutorId($registroAutorId)
  {
    $this->registroAutorId = $registroAutorId;
  }
  public function setRegistroFecha($registroFecha)
  {
    $this->registroFecha = $registroFecha;
  }
  public function setActualizacionAutorId($actualizacionAutorId)
  {
    $this->actualizacionAutorId = $actualizacionAutorId;
  }
  public function setActualizacionFecha($actualizacionFecha)
  {
    $this->actualizacionFecha = $actualizacionFecha;
  }
  public function setStatusNombre($statusNombre)
  {
    $this->statusNombre = $statusNombre;
  }
  public function setRegistroAutor($registroAutor)
  {
    $this->registroAutor = $registroAutor;
  }
  public function setActualizacionAutor($actualizacionAutor)
  {
    $this->actualizacionAutor = $actualizacionAutor;
  }
  public function setZonaHorariaDescripcion($zonaHorariaDescripcion)
  {
    $this->zonaHorariaDescripcion = $zonaHorariaDescripcion;
  }

  /****************************************/
  /**************** Métodos ***************/
  /****************************************/

  public function toJSON()
  {
    $sucursalObj = $this->obtenerObj();
    return json_encode($sucursalObj);
  }

  /**
   * Método para incializar los valores del objeto desde un arreglo de datos
   * @param object $datos
   */
  public function inicializarDesdeObjeto($datos)
  {
    $this->sucursalId           = $datos->sucursal_id;
    $this->clave                = $datos->clave;
    $this->titulo               = $datos->titulo;
    $this->cp                   = $datos->cp;
    $this->direccionCalle       = $datos->direccion_calle;
    $this->direccionColonia     = $datos->direccion_colonia;
    $this->telefono             = $datos->telefono;
    $this->zonaHoraria          = $datos->zona_horaria;
    $this->documentoEncabezado  = $datos->documento_encabezado;
    $this->documentoPiePagina   = $datos->documento_pie_pagina;
    $this->status               = $datos->status;
    $this->metadatos            = $datos->metadatos;
    $this->default              = $datos->default;
    $this->registroAutorId      = $datos->registro_autor_id;
    $this->registroFecha        = $datos->registro_fecha;
    $this->actualizacionAutorId = $datos->actualizacion_autor_id;
    $this->actualizacionFecha   = $datos->actualizacion_fecha;

    $this->statusNombre         = $datos->status_nombre;
    $this->registroAutor        = $datos->registro_autor;
    $this->actualizacionAutor   = $datos->actualizacion_autor;
    $this->zonaHorariaDescripcion = $datos->zona_horaria_descripcion;
  }

  public function obtenerObj()
  {
    return get_object_vars($this);
  }
}
