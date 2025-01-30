<?php

namespace App\Services\BO;

use App\Constantes\ContratoIndividualConst;
use App\Constantes\RedisConst;
use App\Utilerias\FechaUtils;

class RedisBO
{
  /**
   * Método que arma prefijos de redis
   * @param string $tipoPrefijo
   * @param array $datosPrefijo
   * @return void
   */
  public static function armarPrefijoRedis($tipoPrefijo, $datosPrefijo)
  {
    $prefijo = null;
    switch ($tipoPrefijo) {
      case RedisConst::PREFIJO_AUTH_LOGIN_CLIENT:
        $prefijo = RedisConst::PREFIJO_AUTH_LOGIN_CLIENT . $datosPrefijo["contratoGrupalId"] . ":" . $datosPrefijo["telefono"];
        break;
      case RedisConst::PREFIJO_AUTH_SESSION_CLIENT:
        $prefijo = RedisConst::PREFIJO_AUTH_SESSION_CLIENT . $datosPrefijo["contratoIndividualId"] . ":" . $datosPrefijo["telefono"];
        break;
      case RedisConst::PREFIJO_EDITAR_CONTRATO_CLIENT:
        $prefijo = RedisConst::PREFIJO_EDITAR_CONTRATO_CLIENT . $datosPrefijo["contratoIndividualId"] . ":" . $datosPrefijo["telefono"];
        break;
      case RedisConst::PREFIJO_AUTH_SESSION_USER:
        $prefijo = RedisConst::PREFIJO_AUTH_SESSION_USER . $datosPrefijo["usuarioId"];
        break;
      default:
        break;
    }

    return $prefijo;
  }

  /**
   * Método que quita el prefijo del proyecto de todas las keys de redis
   * @param array $keys
   * @return array
   */
  public static function limpiarPrefijoKeys(array $keys){
    $prefijo = env("REDIS_PREFIX");

    $keysLimpias = [];
    foreach ($keys as $key) {
      $keysLimpias[] = str_replace($prefijo, '', $key);
    }

    return $keysLimpias;
  }
}
