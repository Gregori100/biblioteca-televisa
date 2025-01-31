<?php

namespace App\Repositories\Action;

use Illuminate\Support\Facades\DB;

class LibroRepoAction
{
  /**
   * Repo para agregar libros
   * @param array $insert | Datos de insert
   * @return string $id
   */
  public static function agregar(array $insert)
  {
    return DB::table('cat_libros')->insertGetId($insert, 'libro_id');
  }

  /**
   * Repo que edita un libros
   * @param array $update
   * @param string $libroId
   * @return void
   */
  public static function editar($update, $libroId)
  {
    DB::table('cat_libros AS l')
      ->where('l.libro_id', $libroId)
      ->update($update);
  }
}
