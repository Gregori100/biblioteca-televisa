<?php

namespace App\Http\Controllers;

use App\Coordinators\CronCoordinator;
use App\Exceptions\ExceptionHandler;
use App\Utilerias\FechaUtils;
use App\Utilerias\TextoUtils;
use Throwable;

class CronController extends Controller
{
  /**
   * Método para ejecutar el cron
   * @param int $dryrun
   * @param string $token
   */
  public static function testCron($dryrun = 0)
  {
    try {
      CronCoordinator::regresoStatusDisponibilidad($dryrun);
    } catch (Throwable $e) {
      $mensaje = ExceptionHandler::manejarException($e, "Ocurrio un error al ejecutar cron.");
      echo $mensaje;
    }
  }
}
