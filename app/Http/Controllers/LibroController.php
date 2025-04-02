<?php

namespace App\Http\Controllers;

use App\Constantes\CodigoRes;
use App\Coordinators\LibroCoordinator;
use App\Exceptions\ExceptionHandler;
use App\Exceptions\ValidacionException;
use App\Services\LibroService;
use App\Utilerias\ApiResponse;
use App\Utilerias\HashUtils;
use App\Utilerias\TextoUtils;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use stdClass;

class LibroController extends Controller
{
  /**
   * Método para ocupar una libro
   * @param Request $request
   * @return response
   */
  public function ocupar(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        'libroId' => 'required',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      $respuesta = LibroService::ocupar($datos);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Libro ocupado correctamente.", $respuesta)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroController::ocupar()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Método para descargar codigo qr de un libro
   * @param Request $request
   * @return response
   */
  public function descargarCodigoQr(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        'libroId' => 'required',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      $respuesta = LibroService::descargarCodigoQr($datos["libroId"]);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Código descargado correctamente.", $respuesta)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroController::descargarCodigoQr()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /********************************************************************/
  /******************************* WEB ********************************/
  /********************************************************************/
  /**
   * Controller que pre carga información necesaria para
   * el gestor de libros
   * @param Request $request
   * @return void
   */
  public function gestor(Request $request)
  {
    try {
      $datos = $request->all();

      $columnas = $datos['columnas'] ?? [];
      $filtros  = $datos['filtros'] ?? [];
      $limit    = $datos['limit'] ?? [];
      $offset   = $datos['offset'] ?? [];
      $order    = $datos['order'] ?? [];

      $filtros = [
        "busqueda"             => $datos["busqueda"] ?? null,
        "busquedaAutor"        => $datos["busquedaAutor"] ?? null,
        "busquedaEditorial"    => $datos["busquedaEditorial"] ?? null,
        "busquedaGenero"       => $datos["busquedaGenero"] ?? null,
        "busquedaIdioma"       => $datos["busquedaIdioma"] ?? null,
        "busquedaIsbn"         => $datos["busquedaIsbn"] ?? null,
        "statusDisponibilidad" => $datos["statusDisponibilidad"] ?? [],
        "libroId"              => $datos["libroId"] ?? null,
        "ocupar"               => $datos["ocupar"] ?? 0,
      ];
      $order = ["folio_desc"];

      $datosGestor = LibroService::obtenerObjLibros($filtros, $order, true);

      $view = view('libros.LibroGestor', compact(
        'datosGestor',
        'filtros',
      ));

      return ApiResponse::armarMensajeFlashVista($datos, $view, "libros");
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "ContratoGrupalController::gestor()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }
}
