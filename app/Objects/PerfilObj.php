<?php

namespace App\Objects;

/*
|--------------------------------------------------------------------------
| Clase para crear objetos de perfiles de acceso
|--------------------------------------------------------------------------
*/

class PerfilObj
{
  private $perfilId;
  private $clave;
  private $titulo;
  private $descripcion;
  private $status;
  private $global;
  private $registroAutorId;
  private $registroFecha;
  private $actualizacionAutorId;
  private $actualizacionFecha;
  private $statusNombre;

  /****************************************/
  /**************** Getters ***************/
  /****************************************/
  public function getPerfilId()
  {
    return $this->perfilId;
  }
  public function getClave()
  {
    return $this->clave;
  }
  public function getTitulo()
  {
    return $this->titulo;
  }
  public function getDescripcion()
  {
    return $this->descripcion;
  }
  public function getStatus()
  {
    return $this->status;
  }
  public function getGlobal()
  {
    return $this->global;
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

  /****************************************/
  /**************** Setters ***************/
  /****************************************/
  public function setPerfilId($perfilId)
  {
    $this->perfilId = $perfilId;
  }
  public function setClave($clave)
  {
    $this->clave = $clave;
  }
  public function setTitulo($titulo)
  {
    $this->titulo = $titulo;
  }
  public function setDescripcion($descripcion)
  {
    $this->descripcion = $descripcion;
  }
  public function setStatus($status)
  {
    $this->status = $status;
  }
  public function setGlobal($global)
  {
    $this->global = $global;
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

  /****************************************/
  /**************** Métodos ***************/
  /****************************************/

  public function toJSON()
  {
    $usuarioObj = $this->obtenerObj();
    return json_encode($usuarioObj);
  }

  /**
   * Método para incializar los valores del objeto desde un arreglo de datos
   * @param object $datos
   */
  public function inicializarDesdeObjeto($datos)
  {
    $this->perfilId             = $datos->perfil_id;
    $this->clave                = $datos->clave;
    $this->titulo               = $datos->titulo;
    $this->descripcion          = $datos->descripcion;
    $this->status               = $datos->status;
    $this->global               = $datos->global;
    $this->registroAutorId      = $datos->registro_autor_id;
    $this->registroFecha        = $datos->registro_fecha;
    $this->actualizacionAutorId = $datos->actualizacion_autor_id;
    $this->actualizacionFecha   = $datos->actualizacion_fecha;
    $this->statusNombre         = $datos->status_nombre;
  }

  public function obtenerObj()
  {
    return get_object_vars($this);
  }
}
