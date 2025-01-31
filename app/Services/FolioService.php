<?php

namespace App\Services;

use App\Repositories\Action\FolioRepoAction;
use App\Repositories\Data\FolioRepoData;
use App\Services\BO\FolioBO;
use App\Utilerias\TextoUtils;
use Exception;

class FolioService
{
  /**
   * Método para obtener folio
   * Retorna el ultimo folio registrado + 1
   * @param string $tipoFolio
   * @param int | $folioInicial
   * @param string | $serie
   * @throws Exception
   */
  public static function obtenerFolio($tipoFolio, $folioInicial)
  {
    try {
      $key = FolioBO::obtenerTipoFolio($tipoFolio);

      $folio = FolioRepoData::obtenerFolio($key, $folioInicial);
      FolioRepoAction::actualizarFolio($key, $folio);
      return $folio;
    } catch (Exception $e) {
      TextoUtils::agregarLogError($e, "FolioService::obtenerFolio()");
      throw new Exception("Problema en servicio obtener folio. {$e->getMessage()}", 300, $e);
    }
  }
}
