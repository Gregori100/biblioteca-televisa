<?php

namespace App\Objects;

/*
|--------------------------------------------------------------------------
| Clase para crear objetos de usuarios
|--------------------------------------------------------------------------
*/

class UsuarioObj
{
  private $usuarioId;
  private $usuario;
  private $password;
  private $nombreCompleto;
  private $nombreCorto;
  private $email;
  private $telefono;
  private $fechaUltimoAcceso;
  private $status;
  private $statusNombre;
  private $global;
  private $registroAutorId;
  private $registroFecha;
  private $actualizacionAutorId;
  private $actualizacionFecha;
  private $perfilTitulo;
  private $perfilId;
  private $registroAutor;
  private $actualizacionAutor;
  private $rutaFotografiaArchivo;

  /****************************************/
  /**************** Getters ***************/
  /****************************************/
  public function getUsuarioId()
  {
    return $this->usuarioId;
  }
  public function getUsuario()
  {
    return $this->usuario;
  }
  public function getPassword()
  {
    return $this->password;
  }
  public function getNombreCompleto()
  {
    return $this->nombreCompleto;
  }
  public function getNombreCorto()
  {
    return $this->nombreCorto;
  }
  public function getEmail()
  {
    return $this->email;
  }
  public function getTelefono()
  {
    return $this->telefono;
  }
  public function getFechaUltimoAcceso()
  {
    return $this->fechaUltimoAcceso;
  }
  public function getStatus()
  {
    return $this->status;
  }
  public function getStatusNombre()
  {
    return $this->statusNombre;
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
  public function getPerfilTitulo()
  {
    return $this->perfilTitulo;
  }
  public function getPerfilId()
  {
    return $this->perfilId;
  }
  public function getRegistroAutor()
  {
    return $this->registroAutor;
  }
  public function getActualizacionAutor()
  {
    return $this->actualizacionAutor;
  }
  public function getRutaFotografiaArchivo()
  {
    return $this->rutaFotografiaArchivo;
  }

  /****************************************/
  /**************** Setters ***************/
  /****************************************/
  public function setUsuarioId($usuarioId)
  {
    $this->usuarioId = $usuarioId;
  }
  public function setUsuario($usuario)
  {
    $this->usuario = $usuario;
  }
  public function setPassword($password)
  {
    $this->password = $password;
  }
  public function setNombreCompleto($nombreCompleto)
  {
    $this->nombreCompleto = $nombreCompleto;
  }
  public function setNombreCorto($nombreCorto)
  {
    $this->nombreCorto = $nombreCorto;
  }
  public function setEmail($email)
  {
    $this->email = $email;
  }
  public function setTelefono($telefono)
  {
    $this->telefono = $telefono;
  }
  public function setFechaUltimoAcceso($fechaUltimoAcceso)
  {
    $this->fechaUltimoAcceso = $fechaUltimoAcceso;
  }
  public function setStatus($status)
  {
    $this->status = $status;
  }
  public function setStatusNombre($statusNombre)
  {
    $this->statusNombre = $statusNombre;
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
  public function setPerfilTitulo($perfilTitulo)
  {
    $this->perfilTitulo = $perfilTitulo;
  }
  public function setPerfilId($perfilId)
  {
    $this->perfilId = $perfilId;
  }
  public function setRegistroAutor($registroAutor)
  {
    $this->registroAutor = $registroAutor;
  }
  public function setActualizacionAutor($actualizacionAutor)
  {
    $this->actualizacionAutor = $actualizacionAutor;
  }
  public function setRutaFotografiaArchivo($rutaFotografiaArchivo)
  {
    $this->rutaFotografiaArchivo = $rutaFotografiaArchivo;
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
    $this->usuarioId            = $datos->usuario_id;
    $this->usuario              = $datos->usuario;
    $this->password             = $datos->password;
    $this->nombreCompleto       = $datos->nombre_completo;
    $this->nombreCorto          = $datos->nombre_corto;
    $this->email                = $datos->email;
    $this->telefono             = $datos->telefono;
    $this->fechaUltimoAcceso    = $datos->fecha_ultimo_acceso;
    $this->status               = $datos->status;
    $this->statusNombre         = $datos->status_nombre;
    $this->global               = $datos->global;
    $this->registroAutorId      = $datos->registro_autor_id;
    $this->registroFecha        = $datos->registro_fecha;
    $this->actualizacionAutorId = $datos->actualizacion_autor_id;
    $this->actualizacionFecha   = $datos->actualizacion_fecha;
    $this->perfilTitulo         = $datos->perfil_titulo;
    $this->perfilId             = $datos->perfil_id;
    $this->registroAutor        = $datos->registro_autor;
    $this->actualizacionAutor   = $datos->actualizacion_autor;
  }

  public function obtenerObj()
  {
    return get_object_vars($this);
  }
}
