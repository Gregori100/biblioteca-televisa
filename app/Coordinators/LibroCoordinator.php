<?php

namespace App\Coordinators;

use App\Constantes\FolioConsts;
use App\Constantes\LibroConst;
use App\Services\FolioService;
use App\Services\LibroService;
use App\Utilerias\FechaUtils;
use App\Utilerias\TextoUtils;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use UnexpectedValueException;

class LibroCoordinator
{
  /**
   * Coordinator que agrega una libro
   * @param array $datos
   * @return string
   */
  public static function agregar($datos)
  {
    try {
      return DB::transaction(function () use ($datos) {
        // Se obtiene folio de la libro
        $folioLibro = FolioService::obtenerFolio(FolioConsts::KEY_LIBRO, LibroConst::LIBRO_FOLIO_INICIAL);

        return LibroService::agregar($datos, $folioLibro);
      }, 5);
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroCoordinator::agregar()");
      throw new UnexpectedValueException(
        "Problema en servicio agregar libro. {$e->getMessage()} ",
        300,
        $e
      );
    }
  }

  /**
   * Coordinator que elimina una libro
   * @param array $datos
   * @return string
   */
  public static function eliminar($datos)
  {
    try {
      return DB::transaction(function () use ($datos) {
        return LibroService::eliminar($datos);
      }, 5);
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroCoordinator::agregar()");
      throw new UnexpectedValueException(
        "Problema en servicio agregar libro. {$e->getMessage()} ",
        300,
        $e
      );
    }
  }

  /**
   * Coordinator que lee un archivo de excel
   * @param array $datos
   * @return string
   */
  public static function leerExcel($datos)
  {
    try {
      $file = $datos["file"];

      // Cargar el archivo Excel
      $spreadsheet = IOFactory::load($file->getPathname());

      // Obtener la hoja activa
      $worksheet = $spreadsheet->getActiveSheet();

      // Obtener el número de filas y columnas
      $highestRow = $worksheet->getHighestRow();
      $highestColumn = $worksheet->getHighestColumn();
      $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

      $dataBiblioteca = [];

      // Recorrer las filas y columnas y almacenarlas en un array
      for ($row = 1; $row <= $highestRow; ++$row) {
        $rowData = [];
        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
          // Convertir el índice de columna a su letra correspondiente (A, B, C, etc.)
          $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);

          // Obtener el valor de la celda
          $cellValue = $worksheet->getCell($columnLetter . $row)->getValue();

          $rowData[] = $cellValue;
        }
        $dataBiblioteca[] = $rowData;
      }

      array_splice($dataBiblioteca, 0, 3);


      // Armar array
      $inserts = [];
      $folioInicialLibro = 10001;

      foreach ($dataBiblioteca as $data) {
        if ($data[2] == null) {
          continue;
        }

        $insert = [
          'folio'                 => $folioInicialLibro++,
          'nombre'                => $data[2],
          'autor_nombre'          => empty($data[3]) ? null : $data[3],
          'editorial_nombre'      => empty($data[4]) ? null : $data[4],
          'numero_paginas'        => empty($data[5]) ? 0 : $data[5],
          'genero'                => empty(mb_strtoupper(trim($data[6]))) ? null : mb_strtoupper(trim($data[6])),
          'idioma'                => empty(mb_strtoupper(trim($data[7]))) ? null : mb_strtoupper(trim($data[7])),
          'isbn'                  => empty($data[8]) ? null : $data[8],
          'salida_fecha'          => null,
          'regreso_fecha'         => null,
          'observaciones'         => $data[10],
          'motivo_eliminacion'    => null,
          'status'                => LibroConst::LIBRO_STATUS_ACTIVO,
          'status_disponibilidad' => LibroConst::LIBRO_STATUS_DISPONIBILIDAD_DISPONIBLE,
          'registro_autor_id'     => null,
          'registro_fecha'        => FechaUtils::fechaActual(),
        ];

        $inserts[] = $insert;
      }

      $contenido = var_export($inserts, true);

      /*********************************************************/
      /*********************** Pintar TXT **********************/
      /*********************************************************/
      $path = 'temp/datos.txt';

      Storage::disk('public')->put($path, $contenido);

      Storage::disk('public')->delete($path);
      // Storage::disk('public')->deleteDirectory('temp');

    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroCoordinator::agregar()");
      throw new UnexpectedValueException(
        "Problema en servicio agregar libro. {$e->getMessage()} ",
        300,
        $e
      );
    }
  }
}
