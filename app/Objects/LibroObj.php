<?php

namespace App\Objects;

/*
|--------------------------------------------------------------------------
| Clase para crear objetos de libros
|--------------------------------------------------------------------------
*/

class LibroObj
{
  private $libroId;
  private $folio;
  private $nombre;
  private $autorNombre;
  private $editorialNombre;
  private $numeroPaginas;
  private $genero;
  private $idioma;
  private $isbn;
  private $salidaFecha;
  private $regresoFecha;
  private $observaciones;
  private $motivoEliminacion;
  private $status;
  private $statusNombre;
  private $statusDisponibilidad;
  private $registroAutorId;
  private $registroFecha;
  private $actualizacionAutorId;
  private $actualizacionFecha;

  private $hashId;

  /****************************************/
  /**************** Getters ***************/
  /****************************************/
  public function getLibroId()
  {
    return $this->libroId;
  }
  public function getFolio()
  {
    return $this->folio;
  }
  public function getNombre()
  {
    return $this->nombre;
  }
  public function getAutorNombre()
  {
    return $this->autorNombre;
  }
  public function getEditorialNombre()
  {
    return $this->editorialNombre;
  }
  public function getNumeroPaginas()
  {
    return $this->numeroPaginas;
  }
  public function getGenero()
  {
    return $this->genero;
  }
  public function getIdioma()
  {
    return $this->idioma;
  }
  public function getisbn()
  {
    return $this->isbn;
  }
  public function getSalidaFecha()
  {
    return $this->salidaFecha;
  }
  public function getRegresoFecha()
  {
    return $this->regresoFecha;
  }
  public function getObservaciones()
  {
    return $this->observaciones;
  }
  public function getMotivoEliminacion()
  {
    return $this->motivoEliminacion;
  }
  public function getStatus()
  {
    return $this->status;
  }
  public function getStatusNombre()
  {
    return $this->statusNombre;
  }
  public function getStatusDisponibilidad()
  {
    return $this->statusDisponibilidad;
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

  public function getHashId()
  {
    return $this->hashId;
  }

  /****************************************/
  /**************** Setters ***************/
  /****************************************/
  public function setLibroId($libroId)
  {
    $this->libroId = $libroId;
  }
  public function setFolio($folio)
  {
    $this->folio = $folio;
  }
  public function setNombre($nombre)
  {
    $this->nombre = $nombre;
  }
  public function setAutorNombre($autorNombre)
  {
    $this->autorNombre = $autorNombre;
  }
  public function setEditorialNombre($editorialNombre)
  {
    $this->editorialNombre = $editorialNombre;
  }
  public function setNumeroPaginas($numeroPaginas)
  {
    $this->numeroPaginas = $numeroPaginas;
  }
  public function setGenero($genero)
  {
    $this->genero = $genero;
  }
  public function setIdioma($idioma)
  {
    $this->idioma = $idioma;
  }
  public function setisbn($isbn)
  {
    $this->isbn = $isbn;
  }
  public function setSalidaFecha($salidaFecha)
  {
    $this->salidaFecha = $salidaFecha;
  }
  public function setRegresoFecha($regresoFecha)
  {
    $this->regresoFecha = $regresoFecha;
  }
  public function setObservaciones($observaciones)
  {
    $this->observaciones = $observaciones;
  }
  public function setMotivoEliminacion($motivoEliminacion)
  {
    $this->motivoEliminacion = $motivoEliminacion;
  }
  public function setStatus($status)
  {
    $this->status = $status;
  }
  public function setStatusNombre($statusNombre)
  {
    $this->statusNombre = $statusNombre;
  }
  public function setStatusDisponibilidad($statusDisponibilidad)
  {
    $this->statusDisponibilidad = $statusDisponibilidad;
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

  public function setHashId($hashId)
  {
    $this->hashId = $hashId;
  }

  /****************************************/
  /**************** Métodos ***************/
  /****************************************/

  public function toJSON()
  {
    $lineaObj = $this->obtenerObj();
    return json_encode($lineaObj);
  }

  /**
   * Método para incializar los valores del objeto desde un arreglo de datos
   * @param object $datos
   */
  public function inicializarDesdeObjeto($datos)
  {
    $this->libroId              = $datos->libro_id;
    $this->folio                = $datos->folio;
    $this->nombre               = $datos->nombre;
    $this->autorNombre          = $datos->autor_nombre;
    $this->editorialNombre      = $datos->editorial_nombre;
    $this->numeroPaginas        = $datos->numero_paginas;
    $this->genero               = $datos->genero;
    $this->idioma               = $datos->idioma;
    $this->isbn                 = $datos->isbn;
    $this->salidaFecha          = $datos->salida_fecha;
    $this->regresoFecha         = $datos->regreso_fecha;
    $this->observaciones        = $datos->observaciones;
    $this->motivoEliminacion    = $datos->motivo_eliminacion;
    $this->status               = $datos->status;
    $this->statusNombre         = $datos->status_nombre;
    $this->statusDisponibilidad = $datos->status_disponibilidad;
    $this->registroAutorId      = $datos->registro_autor_id;
    $this->registroFecha        = $datos->registro_fecha;
    $this->actualizacionAutorId = $datos->actualizacion_autor_id;
    $this->actualizacionFecha   = $datos->actualizacion_fecha;

    $this->hashId               = $datos->hash_id ?? null;
  }

  public function obtenerObj()
  {
    return get_object_vars($this);
  }
}
