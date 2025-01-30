<?php

namespace App\Objects;

use PDateTime;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

class ArchivoSubirS3
{
  /** @var File|UploadedFile  */
  private $archivo;

  /** @var PDateTime */
  private $fechaGuardado;

  /** @var string  */
  private $nombreGuardar;

  /** @var string  */
  private $tipoArchivo;

  /**
   * ArchivosSubir constructor.
   * @param File|UploadedFile $archivo El contenido del archivo
   * @param PDateTime $fechaGuardado
   * @param string $tipoArchivo El tipo de archivo de las constantes TipoArchivo
   * @param string $nombreGuardar El nombre con el que se desea guardar el archivo (Se debe omitir la extensión)
   */
  public function __construct($archivo, $fechaGuardado, string $tipoArchivo, $nombreGuardar = '')
  {
    $this->tipoArchivo   = $tipoArchivo;
    $this->archivo       = $archivo;
    $this->nombreGuardar = $nombreGuardar;
    $this->fechaGuardado = $fechaGuardado;
  }

  /**
   * @return File|UploadedFile
   */
  public function getArchivo()
  {
    return $this->archivo;
  }

  /**
   * @return PDateTime
   */
  public function getFechaGuardado()
  {
    return $this->fechaGuardado;
  }

  /**
   * @return string
   */
  public function getNombreGuardar(): string
  {
    return $this->nombreGuardar;
  }

  /**
   * @return string
   */
  public function getTipoArchivo(): string
  {
    return $this->tipoArchivo;
  }
}
