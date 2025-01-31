<?php

namespace App\Repositories\Data;

use App\Constantes\LibroConst;
use App\Constantes\QueryConst;
use App\Repositories\RH\LibroRH;
use App\Utilerias\QueryUtils;
use Illuminate\Support\Facades\DB;

class LibroRepoData
{
  /**
   * Repo para obtener lineas
   * @param string $columnas | Columnas que desea obtener
   * @param array  $filtros | Filtros de consulta
   * @param int $limit
   * @param int $offset
   * @param array $order
   * @return array $compras
   * @throws Exception
   */
  public static function listar(
    $columnas = '',
    $filtros = [],
    $limit = null,
    $offset = null,
    $order = []
  ) {
    $query = DB::table('cat_libros AS l')
      ->leftJoin("sys_usuarios AS su1", "su1.usuario_id", "l.registro_autor_id")
      ->leftJoin("sys_usuarios AS su2", "su2.usuario_id", "l.actualizacion_autor_id");

    if (!empty($limit)) {
      $query->limit($limit);
    }
    if (!empty($offset)) {
      $query->offset($offset);
    }

    LibroRH::obtenerColumnasListar($query, $columnas);
    LibroRH::obtenerFiltrosListar($query, $filtros);
    LibroRH::obtenerOrdenListar($query, $order);

    return $query->get()->toArray() ?? [];
    // return $query->paginate(QueryConst::REGISTROS_POR_PAGINA) ?? [];
  }

  /**
   * Repo para obtener generos de libros
   * @param array $filtros
   * @return array
   */
  public static function listarGeneros($filtros = [])
  {
    $query = DB::table('cat_libros AS l')
      ->selectRaw("distinct l.genero")
      ->whereIn('l.status', [LibroConst::LIBRO_STATUS_ACTIVO])
      ->whereNotNull('l.genero')
      ->where('l.genero', '<>', '')
      ->where('l.genero', '<>', ' ')
      ->orderBy('l.genero');

    if (!empty($filtros["busqueda"]) || isset($filtros["busqueda"])) {
      $query->where(function ($query) use ($filtros) {
        $query->whereRaw(
          'unaccent(l.genero) ilike unaccent(?)',
          QueryUtils::busquedaIlike($filtros['busqueda'], 1)
        );
      });
    }

    return $query->get()->toArray() ?: [];
  }

  /**
   * Repo para obtener idiomas de libros
   * @param array $filtros
   * @return array
   */
  public static function listarIdiomas($filtros = [])
  {
    $query = DB::table('cat_libros AS l')
      ->selectRaw("distinct l.idioma")
      ->whereIn('l.status', [LibroConst::LIBRO_STATUS_ACTIVO])
      ->whereNotNull('l.idioma')
      ->where('l.idioma', '<>', '')
      ->where('l.idioma', '<>', ' ')
      ->orderBy('l.idioma');

    if (!empty($filtros["busqueda"]) || isset($filtros["busqueda"])) {
      $query->where(function ($query) use ($filtros) {
        $query->whereRaw(
          'unaccent(l.idioma) ilike unaccent(?)',
          QueryUtils::busquedaIlike($filtros['busqueda'], 1)
        );
      });
    }

    return $query->get()->toArray() ?: [];
  }

  /**
   * Repo para obtener autores de libros
   * @param array $filtros
   * @return array
   */
  public static function listarAutores($filtros = [])
  {
    $query = DB::table('cat_libros AS l')
      ->selectRaw("distinct l.autor_nombre")
      ->whereIn('l.status', [LibroConst::LIBRO_STATUS_ACTIVO])
      ->whereNotNull('l.autor_nombre')
      ->where('l.autor_nombre', '<>', '')
      ->where('l.autor_nombre', '<>', ' ')
      ->orderBy('l.autor_nombre');

    if (!empty($filtros["busqueda"]) || isset($filtros["busqueda"])) {
      $query->where(function ($query) use ($filtros) {
        $query->whereRaw(
          'unaccent(l.autor_nombre) ilike unaccent(?)',
          QueryUtils::busquedaIlike($filtros['busqueda'], 1)
        );
      });
    }

    return $query->get()->toArray() ?: [];
  }

  /**
   * Repo para obtener editoriales de libros
   * @param array $filtros
   * @return array
   */
  public static function listarEditoriales($filtros = [])
  {
    $query = DB::table('cat_libros AS l')
      ->selectRaw("distinct l.editorial_nombre")
      ->whereIn('l.status', [LibroConst::LIBRO_STATUS_ACTIVO])
      ->whereNotNull('l.editorial_nombre')
      ->where('l.editorial_nombre', '<>', '')
      ->where('l.editorial_nombre', '<>', ' ')
      ->orderBy('l.editorial_nombre');

    if (!empty($filtros["busqueda"]) || isset($filtros["busqueda"])) {
      $query->where(function ($query) use ($filtros) {
        $query->whereRaw(
          'unaccent(l.editorial_nombre) ilike unaccent(?)',
          QueryUtils::busquedaIlike($filtros['busqueda'], 1)
        );
      });
    }

    return $query->get()->toArray() ?: [];
  }
}
