<?php

namespace App\Repositories\Action;

use App\Utilerias\TextoUtils;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class FolioRepoAction
{
  /**
   * Repo para actualizar ultimo folio
   */
  public static function actualizarFolio($key, $folio)
  {
    try {
      DB::table('cat_folios')
        ->updateOrInsert(['key' => $key], ['folio' => $folio]);
    } catch (QueryException $e) {
      TextoUtils::agregarLogError($e, "FolioRepoAction::actualizarFolio()");
      throw new Exception("Problema en repo actualizar folio.", 300, $e);
    }
  }
}
