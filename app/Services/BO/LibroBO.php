<?php

namespace App\Services\BO;

use App\Constantes\LibroConst;
use App\Utilerias\FechaUtils;
use Exception;

class LibroBO
{
  /**
   * Método que arma insert de una libro
   * @param array $datos
   * @param int $folioLibro
   * @return array $insert
   */
  public static function armarInsertLibro(array $datos, $folioLibro)
  {
    $insert = [];

    $insert["folio"]                 = $folioLibro;;
    $insert["nombre"]                = trim($datos["nombre"]);
    $insert["autor_nombre"]          = trim($datos["autorNombre"]);
    $insert["editorial_nombre"]      = trim($datos["editorialNombre"]);
    $insert["numero_paginas"]        = $datos["numeroPaginas"];
    $insert["genero"]                = mb_strtoupper(trim($datos["generoNombre"]));
    $insert["idioma"]                = mb_strtoupper(trim($datos["idiomaNombre"]));
    $insert["isbn"]                  = $datos["isbn"];
    $insert["observaciones"]         = trim($datos["observaciones"]);
    $insert["status"]                = LibroConst::LIBRO_STATUS_ACTIVO;
    $insert["status_disponibilidad"] = LibroConst::LIBRO_STATUS_DISPONIBILIDAD_DISPONIBLE;
    $insert["registro_autor_id"]     = $datos["usuarioId"] ?? null;
    $insert["registro_fecha"]        = FechaUtils::fechaActual();

    return $insert;
  }

  /**
   * Método que arma update de una libro
   * @param array $datos
   * @param bool $esEdit
   * @return array $update
   */
  public static function armarUpdateLibro(array $datos, bool $esEdit = false)
  {
    $update = [];

    if ($esEdit) {
      $update["nombre"]                = trim($datos["nombre"]);
      $update["autor_nombre"]          = trim($datos["autorNombre"]);
      $update["editorial_nombre"]      = trim($datos["editorialNombre"]);
      $update["numero_paginas"]        = $datos["numeroPaginas"];
      $update["genero"]                = mb_strtoupper(trim($datos["generoNombre"]));
      $update["idioma"]                = mb_strtoupper(trim($datos["idiomaNombre"]));
      $update["isbn"]                  = $datos["isbn"];
      $update["observaciones"]         = trim($datos["observaciones"]);
    } else {
      $update["status"]                = LibroConst::LIBRO_STATUS_ELIMINADO;
      $update["status_disponibilidad"] = LibroConst::LIBRO_STATUS_DISPONIBILIDAD_RETIRADO;
    }

    $update["actualizacion_autor_id"]  = $datos["usuarioId"] ?? null;
    $update["actualizacion_fecha"]     = FechaUtils::fechaActual();

    return $update;
  }

  /**
   * Método que arma update de status de disponibilidad de libro
   * @param array $datos
   * @param bool $ocuparLibro
   * @return array $update
   */
  public static function armarUpdateStatusDisponibilidadLibro(array $datos, $ocuparLibro = true)
  {
    $update = [];

    if($ocuparLibro){
      $update["salida_fecha"]         = FechaUtils::fechaActual();
      $update["regreso_fecha"]        = FechaUtils::sumarDiasFechaActual(null, 14);
    } else {
      $update["salida_fecha"]         = null;
      $update["regreso_fecha"]        = null;
    }

    $update["status_disponibilidad"]  = $datos["nuevoStatusDisponibilidad"];
    $update["actualizacion_autor_id"] = $datos["usuarioId"] ?? null;
    $update["actualizacion_fecha"]    = FechaUtils::fechaActual();

    return $update;
  }
}
