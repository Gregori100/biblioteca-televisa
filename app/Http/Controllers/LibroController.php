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
   * Listar libros
   * @param Request $request
   * @return void
   */
  public function listar(Request $request)
  {
    try {
      $datos = $request->all();

      $columnas = $datos['columnas'] ?? [];
      $filtros  = $datos['filtros'] ?? [];
      $limit    = $datos['limit'] ?? [];
      $offset   = $datos['offset'] ?? [];
      $order    = $datos['order'] ?? [];

      $libros = LibroService::listar($columnas, $filtros, $limit, $offset, $order);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Listado de libros obtenido correctamente.", $libros)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroController::listar()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Listar generos
   * @param Request $request
   * @return void
   */
  public function listarGeneros(Request $request)
  {
    try {
      $datos = $request->all();

      $filtros  = $datos['filtros'] ?? [];

      $generos = LibroService::listarGeneros($filtros);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Listado de generos obtenido correctamente.", $generos)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroController::listarGeneros()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Listar idiomas
   * @param Request $request
   * @return void
   */
  public function listarIdiomas(Request $request)
  {
    try {
      $datos = $request->all();

      $filtros  = $datos['filtros'] ?? [];

      $idiomas = LibroService::listarIdiomas($filtros);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Listado de idiomas obtenido correctamente.", $idiomas)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroController::listarIdiomas()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Listar autores
   * @param Request $request
   * @return void
   */
  public function listarAutores(Request $request)
  {
    try {
      $datos = $request->all();

      $filtros  = $datos['filtros'] ?? [];

      $autores = LibroService::listarAutores($filtros);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Listado de autores obtenido correctamente.", $autores)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroController::listarAutores()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Listar editoriales
   * @param Request $request
   * @return void
   */
  public function listarEditoriales(Request $request)
  {
    try {
      $datos = $request->all();

      $filtros  = $datos['filtros'] ?? [];

      $editoriales = LibroService::listarEditoriales($filtros);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Listado de editoriales obtenido correctamente.", $editoriales)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroController::listarEditoriales()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Método que agrega una libro
   * @param Request $request
   * @return response
   */
  public function agregar(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        "nombre"        => "required",
        "numeroPaginas" => "required",
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      $libroId = LibroCoordinator::agregar($datos);

      // $respuesta = new stdClass();
      // $respuesta->libroId = $libroId;
      // $respuesta->hashId  = HashUtils::getHash($libroId);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Libro agregado correctamente.", true)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroController::agregar()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Método que edita una libro
   * @param Request $request
   * @return response
   */
  public function editar(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        'libroId'       => 'required',
        "nombre"        => "required",
        "numeroPaginas" => "required",
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      LibroService::editar($datos);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Libro editado correctamente.", true)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroController::editar()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

  /**
   * Método que elimina una libro
   * @param Request $request
   * @return response
   */
  public function eliminar(Request $request)
  {
    try {
      $datos = $request->all();

      $validator = Validator::make($datos, [
        'libroId' => 'required',
      ]);

      if ($validator->stopOnFirstFailure()->fails()) {
        throw new ValidacionException(TextoUtils::obtenerMensajesValidator($validator->getMessageBag()));
      }

      $respuesta = LibroCoordinator::eliminar($datos);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Libro eliminado correctamente.", $respuesta)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroController::eliminar()");
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    }
  }

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
   * Método que lee un archivo excel
   * @param Request $request
   * @return response
   */
  public function leerExcel(Request $request)
  {
    try {
      $datos = $request->all();

      LibroCoordinator::leerExcel($datos);

      return response(
        ApiResponse::build(CodigoRes::EXITO, "Archivo leeído correctamente.", true)
      );
    } catch (ValidacionException $e) {
      return response(ApiResponse::build(CodigoRes::ERROR, $e->getMessage()));
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "LibroController::leerExcel()");
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
      ];
      $order = ["folio_asc"];

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
