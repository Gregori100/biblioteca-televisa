<?php

namespace App\Services\BO;

use App\Constantes\FolioConsts;

class FolioBO
{
  public static function obtenerTipoFolio($tipoOperacion)
  {
    $tipoFolioPorTipoOperacion = [
      FolioConsts::KEY_LINEA => FolioConsts::KEY_LINEA,
      FolioConsts::KEY_MODELO => FolioConsts::KEY_MODELO,
      FolioConsts::KEY_PAQUETE => FolioConsts::KEY_PAQUETE,
      FolioConsts::KEY_CONTRATO_INDIVIDUAL => FolioConsts::KEY_CONTRATO_INDIVIDUAL,
      FolioConsts::KEY_ARTICULO_PAQUETE => FolioConsts::KEY_ARTICULO_PAQUETE,
      FolioConsts::KEY_ARTICULO => FolioConsts::KEY_ARTICULO,
      FolioConsts::KEY_COMBINACION => FolioConsts::KEY_COMBINACION,
      FolioConsts::KEY_PAGO => FolioConsts::KEY_PAGO,
      FolioConsts::KEY_COMPROBANTE => FolioConsts::KEY_COMPROBANTE,
      FolioConsts::KEY_LIBRO => FolioConsts::KEY_LIBRO,
    ];

    return $tipoFolioPorTipoOperacion[$tipoOperacion];
  }
}
