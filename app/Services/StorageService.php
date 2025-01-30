<?php

namespace App\Services;

use App\Constantes\ArchivoConst;
use App\Constantes\TipoArchivoConst;
use App\Exceptions\BusinessException;
use App\Objects\ArchivoSubirS3;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PDateTime;

class StorageService
{
  /**
   * Obtiene la carpeta del storage en base a su tipo
   *
   * @param string $tipoArchivo La constante de  tipo archivo
   * @return string La carpeta del storage
   * @throws Exception
   */
  public static function obtenerCarpetaTipoArchivo(string $tipoArchivo)
  {
    $s3_subFolder = env('AWS_SUBFOLDER');

    $path = $s3_subFolder;
    switch ($tipoArchivo) {
      case TipoArchivoConst::ARTICULOS:
        $path .= ArchivoConst::RUTA_STORAGE_S3_ARTICULOS;
        break;
      case TipoArchivoConst::USUARIOS:
        $path .= ArchivoConst::RUTA_STORAGE_S3_USUARIOS;
        break;
      case TipoArchivoConst::PAQUETES:
        $path .= ArchivoConst::RUTA_STORAGE_S3_PAQUETES;
        break;
      case TipoArchivoConst::MODELOS:
        $path .= ArchivoConst::RUTA_STORAGE_S3_MODELOS;
        break;
      case TipoArchivoConst::ARTICULOS_PAQUETE:
        $path .= ArchivoConst::RUTA_STORAGE_S3_ARTICULOS_PAQUETE;
        break;
      case TipoArchivoConst::CONTRATOS_GRUPALES:
        $path .= ArchivoConst::RUTA_STORAGE_S3_CONTRATOS_GRUPALES;
        break;
      default:
        throw new Exception('Tipo de archivo inválido');
    }
    return $path;
  }

  /**
   * Método para subir archivos sin considerar las carpetas generadas con la fecha
   * @param array $archivos
   * @throws Exception
   */
  public static function subirSinCarpetaFecha(array $archivos)
  {
    try {

      if (empty($archivos)) {
        throw new Exception('Debe especificar el arreglo de archivos a subir.');
      }

      Log::debug('Subiendo archivos ' . count($archivos));

      foreach ($archivos as $i => $archivo) {
        $file    = $archivo->getArchivo();
        $carpeta = self::obtenerCarpetaTipoArchivo($archivo->getTipoArchivo());
        $ruta    = $carpeta;
        Log::debug('Guardando archivo: ' . $ruta . $archivo->getNombreGuardar());
        Storage::putFileAs($ruta, $file, $archivo->getNombreGuardar());
      }
    } catch (Exception $e) {
      throw $e;
    }
  }

  /**
   * Valida si existe un archivo en el storage
   *
   * @return bool
   * @throws Exception
   */
  public static function existeArchivo($archivo): bool
  {
    try {
      $carpeta = self::obtenerCarpetaTipoArchivo($archivo->getTipoArchivo());
      $ruta = $carpeta . $archivo->getNombreGuardar();

      Log::info("Verificando existencia de archivo: $ruta");

      return Storage::exists($ruta);
    } catch (Exception $e) {
      Log::error($e->getMessage());
      throw $e;
    }
  }

  /**
   * Obtiene la carpeta del storage de el archivo buscado
   *
   * @param ArchivoSubirS3 $archivo
   * @return string La carpeta del storage
   * @throws Exception
   */
  public static function obtenerRutaArchivo(ArchivoSubirS3 $archivo)
  {
    $carpeta       = self::obtenerCarpetaTipoArchivo($archivo->getTipoArchivo());
    $nombreArchivo = $archivo->getNombreGuardar();
    return Storage::url($carpeta . $nombreArchivo);
  }

  /**
   * Método que elimina un archivo
   * @param string $rutaYArchivo
   * @param string $tipoArchivo
   * @param bool $verficacionExistencia | Booleano que indica si realizar la verificacion de existencia de archivo
   * @return bool
   */
  public static function eliminarArchivo(string $rutaYArchivo, string $tipoArchivo, $verficacionExistencia = true)
  {
    // Validar que se ha proporcionado la ruta del archivo y el tipo de archivo
    if (empty($rutaYArchivo)) {
      throw new BusinessException('Debe especificar el nombre del archivo a eliminar');
    }

    if (empty($tipoArchivo)) {
      throw new BusinessException('Debe especificar el tipo de archivo');
    }

    // Obtener la carpeta correspondiente al tipo de archivo
    $carpeta = self::obtenerCarpetaTipoArchivo($tipoArchivo);

    // Construir la ruta completa del archivo
    $ruta = $carpeta . $rutaYArchivo;
    Log::info("Intentando eliminar archivo $ruta");

    // Verificar si el archivo existe
    if ($verficacionExistencia && !Storage::exists($ruta)) {
      throw new BusinessException("El archivo no existe: $ruta");
    }

    // Eliminar el archivo
    Storage::delete($ruta);

    return true;
  }
}
