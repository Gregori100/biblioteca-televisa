<?php

namespace App\Utilerias;

use App\Constantes\MensajeFlashVistaConst;
use App\Exceptions\ExceptionHandler;
use Exception;
use Illuminate\View\View;

class ApiResponse
{
  /**
   * Construye una respuesta estructurada del api
   *
   * @param int $codigo
   * @param string $mensaje
   * @param mixed $datos
   * @return array
   */
  public static function build(int $codigo, string $mensaje, $datos = null)
  {
    $respuesta = [];

    $respuesta['codigo']  = $codigo;
    $respuesta['mensaje'] = $mensaje;
    $respuesta['data']   = $datos;

    return $respuesta;
  }
  /**
   * Metodo que arma mensaje flash para las vistas del sistema, en caso de necesitar
   * @param array $datos
   * @param View $view
   * @param string $vista
   * @return View
   */
  public static function armarMensajeFlashVista(array $datos, View &$view, string $vista)
  {
    $tipoMensaje = null;
    $mensajeAccion = "";

    foreach (['exito', 'advertencia', 'error'] as $tipo) {
      if (
        isset(MensajeFlashVistaConst::MENSAJE_FLASH_VISTA[$vista]) &&
        isset($datos[$tipo]) &&
        array_key_exists($datos[$tipo], MensajeFlashVistaConst::MENSAJE_FLASH_VISTA[$vista][$tipo])
      ) {
        $tipoMensaje   = $tipo;
        $mensajeAccion = MensajeFlashVistaConst::MENSAJE_FLASH_VISTA[$vista][$tipo][$datos[$tipo]];
        break;
      }
    }

    if ($tipoMensaje) {
      $view->with("mensajeAccion", ["tipo" => $tipoMensaje, "mensaje" => $mensajeAccion]);
    } else {
      $view->with("mensajeAccion", false);
    }

    return $view;
  }

  /**
   * Metodo que arma mensaje flash para las vistas del sistema, en caso de necesitar
   * @param array $datos
   * @param View $view
   * @param string $controlador
   * @return View
   */
  public static function armarMensajeErrorFlashVista(Exception $e, View &$view, string $controlador)
  {
    TextoUtils::agregarLogError($e, $controlador);

    // $view->with("mensajeAccion", ["tipo" => "error", "mensaje" => "Ocurrió un error inesperado."]);
    $view->with("mensajeAccion", ["tipo" => "error", "mensaje" => "Ocurrió un error inesperado." . $e->getMessage()]);

    return $view;
  }

  /**
   * Método que arma mensaje de error en gestores
   * @param Exception $e
   * @param string $vistaGestor
   * @param string $mensajeError
   * @param array $datosVacios
   * @return void
   */
  public static function manejarErrorEnGestor(Exception $e, string $vistaGestor, string $mensajeError, array $datosVacios = [])
  {
    $codigo = ExceptionHandler::manejarException($e, $mensajeError);

    $datosVacios["mensajeAccion"] = ["tipo" => "error", "mensaje" => $codigo];

    return view($vistaGestor)
      ->with('filtros', [])
      ->with($datosVacios);
  }
}
