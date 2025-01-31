<?php

namespace App\Repositories\Data;

use App\Utilerias\TextoUtils;
use Exception;
use Illuminate\Support\Facades\DB;

class FolioRepoData
{
  /**
   * Repo para obtener folio maximo por tipo de operacion
   * @throws Exception
   */
  public static function obtenerFolio($key, $folioInicial)
  {
    try {
      $ultimoFolio = DB::table('cat_folios AS cf')
        ->lockForUpdate()
        ->select('cf.folio')
        ->where('cf.key', $key)
        ->get()
        ->first();

      if (!$ultimoFolio)
        return $folioInicial;
      else
        return $ultimoFolio->folio + 1;
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "FolioRepoData::obtenerFolio()");
      throw new Exception("Problema en consulta obtener folio.", 300, $e);
    }
  }
}
