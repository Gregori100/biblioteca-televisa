<?php

namespace App\Services;

use App\Constantes\LibroConst;
use App\Exceptions\ValidacionException;
use App\Objects\LibroObj;
use App\Repositories\Action\LibroRepoAction;
use App\Repositories\Data\LibroRepoData;
use App\Services\BO\LibroBO;
use App\Utilerias\HashUtils;
use App\Utilerias\TextoUtils;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use stdClass;

class LibroService
{
  /**
   * Listar libros
   *
   * @param string $columnas
   * @param array $filtros
   * @param int $limit
   * @param int $offset
   * @param array $order
   * @return mixed
   */
  public static function listar(
    $columnas = '',
    $filtros = [],
    $limit = null,
    $offset = null,
    $order = []
  ) {
    $libros = LibroRepoData::listar($columnas, $filtros, $limit, $offset, $order);

    foreach ($libros as &$libro) {
      $libro->hash_id = HashUtils::getHash($libro->libro_id);
    }

    return $libros;
  }

  /**
   * Listar generos libros
   * @param array $filtros
   * @return array
   */
  public static function listarGeneros($filtros = [])
  {
    return LibroRepoData::listarGeneros($filtros);
  }

  /**
   * Listar idiomas libros
   * @param array $filtros
   * @return array
   */
  public static function listarIdiomas($filtros = [])
  {
    return LibroRepoData::listarIdiomas($filtros);
  }

  /**
   * Listar autores libros
   * @param array $filtros
   * @return array
   */
  public static function listarAutores($filtros = [])
  {
    return LibroRepoData::listarAutores($filtros);
  }

  /**
   * Listar editoriales libros
   * @param array $filtros
   * @return array
   */
  public static function listarEditoriales($filtros = [])
  {
    return LibroRepoData::listarEditoriales($filtros);
  }

  /**
   * Service que agrega un libro
   * @param array $datos
   * @param int $folioLibro
   * @return void
   */
  public static function agregar(array $datos, int $folioLibro)
  {
    // Validación agregar
    self::validarAgregarLibro($datos);

    $insert = LibroBO::armarInsertLibro($datos, $folioLibro);
    return LibroRepoAction::agregar($insert);
  }

  /**
   * Service que edita una libro
   * @param array $datos
   * @return void
   */
  public static function editar(array $datos)
  {
    // Validar editar
    self::validarEditarLibro($datos);

    $update = LibroBO::armarUpdateLibro($datos, true);
    LibroRepoAction::editar($update, $datos["libroId"]);
  }

  /**
   * Service que elimina una libro
   * @param array $datos
   * @return void
   */
  public static function eliminar(array $datos)
  {
    // Validación
    self::validarEliminarLibro($datos);

    $update = LibroBO::armarUpdateLibro($datos);
    LibroRepoAction::editar($update, $datos["libroId"]);
  }

  /**
   * Service para ocupar un libro
   * @param array $datos
   * @return void
   */
  public static function ocupar(array $datos)
  {
    // Validación
    self::validarOcuparLibro($datos);

    $datos["nuevoStatusDisponibilidad"] = LibroConst::LIBRO_STATUS_DISPONIBILIDAD_OCUPADO;
    $update = LibroBO::armarUpdateStatusDisponibilidadLibro($datos);
    LibroRepoAction::editar($update, $datos["libroId"]);
  }

  /**
   * Service para desocupar un libro
   * @param array $datos
   * @return void
   */
  public static function desocuparLibro(array $datos)
  {
    // Validación
    self::validarDesocuparLibro($datos);

    $datos["nuevoStatusDisponibilidad"] = LibroConst::LIBRO_STATUS_DISPONIBILIDAD_DISPONIBLE;
    $update = LibroBO::armarUpdateStatusDisponibilidadLibro($datos, false);
    LibroRepoAction::editar($update, $datos["libroId"]);
  }

  /**
   * Método que obtiene una libro
   * @param string $libroId
   * @param bool $validarStatus
   * @return LibroObj
   */
  public static function obtenerLibro($libroId, $validarStatus = true)
  {
    // Listar libros
    $registroLibro =  self::listar("", ["libroId" => $libroId]);

    if (empty($registroLibro)) {
      throw new Exception("No se pudo obtener registro de libro.", 300);
    }

    $libroObj = new LibroObj();
    $libroObj->inicializarDesdeObjeto($registroLibro[0]);

    if ($validarStatus && $libroObj->getStatus() != LibroConst::LIBRO_STATUS_ACTIVO) {
      throw new Exception("La libro seleccionada ha cambiado de status, favor de recargar la página.", 300);
    }

    return $libroObj;
  }

  /**
   * Método que obtiene registros de libros
   * @param array $filtros
   * @param array $order
   * @param bool $obtenerDatosGestor
   * @return mixed
   */
  public static function obtenerObjLibros($filtros = [], $order = [], $obtenerDatosGestor = false)
  {
    // Listar libros
    $registrosLibros = self::listar("", $filtros, null, null, $order);

    $librosObjs = [];
    foreach ($registrosLibros as $registro) {
      $libroObj = new LibroObj();
      $libroObj->inicializarDesdeObjeto($registro);

      $librosObjs[] = $obtenerDatosGestor ? $libroObj->obtenerObj() : $libroObj;
    }

    return $librosObjs;

    // if(!$obtenerDatosGestor){
    //   return $librosObjs;
    // }

    // $datosGestor = new stdClass();
    // $datosGestor->registros   = $librosObjs;
    // $datosGestor->perPage     = $registrosLibros->perPage();
    // $datosGestor->currentPage = $registrosLibros->currentPage();
    // $datosGestor->total       = $registrosLibros->total();
    // $datosGestor->lastPage    = $registrosLibros->lastPage();

    // return $datosGestor;
  }

  /**
   * Método que descarga codigo qr de libro
   * @param string $libroId
   * @return stdClass
   */
  public static function descargarCodigoQr($libroId)
  {
    // Obtener Obj libro
    $libroObj = self::obtenerLibro($libroId, false);

    // Generar URL
    $ip  = env("APP_URL");
    $url = "{$ip}/libros?busqueda={$libroObj->getFolio()}&libroId={$libroObj->getLibroId()}&ocupar=1";

    // Verificar si la carpeta "temp" no existe y crearla si es necesario
    if (!Storage::disk('public')->exists("temp")) {
      Storage::disk('public')->makeDirectory("temp");
    }

    $archivo = new stdClass();

    try {
      // Crear el código QR
      $qrCode = new QrCode($url);

      // Escribir la imagen QR en formato PNG
      $writer = new PngWriter();
      $result = $writer->write($qrCode);

      // Guardar el QR temporalmente
      $tempQrPath = storage_path("app/public/temp/temp_qr_{$libroObj->getFolio()}.png");
      $result->saveToFile($tempQrPath);

      // Armar retono
      $archivo->base64    = base64_encode(file_get_contents($tempQrPath));
      $archivo->nombre    = $libroObj->getFolio() . ".png";
      $archivo->extension = "png";

      unlink($tempQrPath);
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroService::descargarCodigoQr()");
    }

    return $archivo;
  }

  /********************************************************************/
  /*************************** VALIDACIONES ***************************/
  /********************************************************************/
  /**
   * Método que valida el agregar una libro
   * @param array $datos
   * @return void
   */
  private static function validarAgregarLibro($datos)
  {
    $registrosLibros = self::listar("", [
      "nombre" => $datos["nombre"],
      "status" => [LibroConst::LIBRO_STATUS_ACTIVO],
    ]);

    if (!empty($registrosLibros)) {
      throw new Exception("Ya existe alguna libro con este nombre, favor de revisar. {$datos["nombre"]}");
    }
  }

  /**
   * Método que valida el editar una libro
   * @param array $datos
   * @return void
   */
  private static function validarEditarLibro($datos)
  {
    $registrosLibros = self::listar("", [
      "libroIdNot" => $datos["libroId"],
      "nombre"     => $datos["nombre"],
      "status"     => [LibroConst::LIBRO_STATUS_ACTIVO],
    ]);

    if (!empty($registrosLibros)) {
      throw new Exception("Ya existe alguna libro con este nombre, favor de revisar. {$datos["nombre"]}");
    }

    $registroLibro = self::listar("", ["libroId" => $datos["libroId"]]);

    if (empty($registroLibro)) {
      throw new Exception("No se encontró registro de la libro seleccionada, favor de recargar la página. {$datos["nombre"]}");
    }

    if ($registroLibro[0]->status != LibroConst::LIBRO_STATUS_ACTIVO) {
      throw new Exception("El status de la libro a cambiado, favor de recargar la página. {$datos["nombre"]}");
    }
  }

  /**
   * Método que valida el eliminar una libro
   * @param array $datos
   * @return void
   */
  private static function validarEliminarLibro($datos)
  {
    $registroLibro = self::listar("", ["libroId" => $datos["libroId"]]);

    if (empty($registroLibro)) {
      throw new Exception("No se encontró registro de la libro seleccionada, favor de recargar la página. {$datos["nombre"]}");
    }

    if ($registroLibro[0]->status != LibroConst::LIBRO_STATUS_ACTIVO) {
      throw new Exception("El status de la libro a cambiado, favor de recargar la página. {$datos["nombre"]}");
    }
  }

  /**
   * Método que valida el apartar una libro
   * @param array $datos
   * @return void
   */
  private static function validarOcuparLibro($datos)
  {
    $registroLibro = self::listar("", ["libroId" => $datos["libroId"]]);

    if (empty($registroLibro)) {
      throw new Exception("No se encontró registro de la libro seleccionada, favor de recargar la página. {$datos["nombre"]}");
    }

    if (
      $registroLibro[0]->status != LibroConst::LIBRO_STATUS_ACTIVO ||
      $registroLibro[0]->status_disponibilidad != LibroConst::LIBRO_STATUS_DISPONIBILIDAD_DISPONIBLE
    ) {
      throw new Exception("El status de la libro a cambiado, favor de recargar la página. {$datos["nombre"]}");
    }
  }

  /**
   * Método que valida el apartar una libro
   * @param array $datos
   * @return void
   */
  private static function validarDesocuparLibro($datos)
  {
    $registroLibro = self::listar("", ["libroId" => $datos["libroId"]]);

    if (empty($registroLibro)) {
      throw new Exception("No se encontró registro del libro seleccionado: {$datos["folio"]}");
    }

    if (
      $registroLibro[0]->status != LibroConst::LIBRO_STATUS_ACTIVO ||
      $registroLibro[0]->status_disponibilidad != LibroConst::LIBRO_STATUS_DISPONIBILIDAD_OCUPADO
    ) {
      throw new Exception("El status de la libro a cambiado: {$datos["folio"]}");
    }
  }
}
