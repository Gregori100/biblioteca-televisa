<?php

namespace App\Coordinators;

use App\Constantes\FolioConsts;
use App\Constantes\LibroConst;
use App\Exceptions\ExceptionHandler;
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

class CronCoordinator
{
  /**
   * Método para ejecutar el cron de notificación de caducidades
   * @param int $dryrun
   */
  public static function regresoStatusDisponibilidad($dryrun = 0)
  {
    $librosStatusModificados   = 0;
    $librosStatusNoModificados = 0;

    echo TextoUtils::obtenerMensajeLogEndpoint("========================================================");
    echo TextoUtils::obtenerMensajeLogEndpoint("Inicia proceso de cambio de status de disponibilidad de libros");
    echo TextoUtils::obtenerMensajeLogEndpoint("Fecha ejecución: " . FechaUtils::fechaActual());
    echo TextoUtils::obtenerMensajeLogEndpoint("========================================================");
    echo TextoUtils::obtenerMensajeLogEndpoint("");

    $registrosLibrosConFechaPasada = LibroService::listar("", [
      "fechaRegresoFinal" => FechaUtils::fechaActual(null, true),
    ]);

    echo TextoUtils::obtenerMensajeLogEndpoint("========================================================");
    echo TextoUtils::obtenerMensajeLogEndpoint("Se encontraron " . sizeof($registrosLibrosConFechaPasada) . " libros para devolución.");
    echo TextoUtils::obtenerMensajeLogEndpoint("========================================================");
    echo TextoUtils::obtenerMensajeLogEndpoint("");

    foreach ($registrosLibrosConFechaPasada as $libroObj) {
      if ($dryrun == 0) {
        echo TextoUtils::obtenerMensajeLogEndpoint("========================================================");
        echo TextoUtils::obtenerMensajeLogEndpoint("Modo simulacro no se actualiza el registro: {$libroObj->folio}");
        echo TextoUtils::obtenerMensajeLogEndpoint("========================================================");
        echo TextoUtils::obtenerMensajeLogEndpoint("");
      } else {
        try {
          $dataActualizar = [
            "libroId" => $libroObj->libro_id,
            "folio"   => $libroObj->folio,
          ];

          LibroService::desocuparLibro($dataActualizar);

          echo TextoUtils::obtenerMensajeLogEndpoint("========================================================");
          echo TextoUtils::obtenerMensajeLogEndpoint("Status de libro actualizado exitosamente.");
          echo TextoUtils::obtenerMensajeLogEndpoint("========================================================");
          echo TextoUtils::obtenerMensajeLogEndpoint("");
          $librosStatusModificados++;
        } catch (Exception $e) {
          $librosStatusNoModificados++;
          echo TextoUtils::obtenerMensajeLogEndpoint("========================================================");
          echo TextoUtils::obtenerMensajeLogEndpoint("Ocurrio un error al actualizar status del libro.");
          echo TextoUtils::obtenerMensajeLogEndpoint("========================================================");
          echo TextoUtils::obtenerMensajeLogEndpoint("");
          ExceptionHandler::manejarException($e, "Ocurrio un error al actualizar status de libro.");
        }
      }

      echo TextoUtils::obtenerMensajeLogEndpoint("========================================================");
      echo TextoUtils::obtenerMensajeLogEndpoint("Finaliza proceso de actualización para libro: {$libroObj->folio}");
      echo TextoUtils::obtenerMensajeLogEndpoint("========================================================");
      echo TextoUtils::obtenerMensajeLogEndpoint("");
    }

    echo TextoUtils::obtenerMensajeLogEndpoint("========================================================");
    echo TextoUtils::obtenerMensajeLogEndpoint("Finaliza proceso de actualización de status de libros");
    if ($dryrun == 1) {
      echo TextoUtils::obtenerMensajeLogEndpoint("Libros encontrados: " . sizeof($registrosLibrosConFechaPasada));
      echo TextoUtils::obtenerMensajeLogEndpoint("Libros actualizados exitosamente: " . $librosStatusModificados);
      echo TextoUtils::obtenerMensajeLogEndpoint("Libros no actualizados: " . $librosStatusNoModificados);
    }
    echo TextoUtils::obtenerMensajeLogEndpoint("========================================================");
  }
}
