<?php

namespace App\Services;

use App\Constantes\RedisConst;
use App\Repositories\Action\ContratoIndividualRepoAction;
use App\Repositories\Data\ContratoIndividualRepoData;
use App\Services\BO\ContratoIndividualBO;
use App\Services\BO\RedisBO;
use App\Utilerias\TextoUtils;
use Illuminate\Support\Facades\Redis;

class RedisService
{
  /**
   * Método que guarda objeto en redis
   * @param string $tipoPrefijo | Constante sobre cual tipo de predijo
   * @param array $datosPrefijo | array de datos que se concatenan en el prefijo
   * @param mixed $objRedis | Objeto que se guarda en redis
   * @param string $tiempoExpiracion | tiempo que tarda en expirar la llave
   * @param bool $guardarToken
   * @return string $tokenRedis
   */
  public static function guardarKeyRedis(
    $tipoPrefijo,
    $datosPrefijo,
    $guardarToken = false,
    $objRedis = [],
    $tiempoExpiracion = RedisConst::TTL_10_MINUTOS
  ) {
    // Se crea prefijo
    $prefijoToken = RedisBO::armarPrefijoRedis($tipoPrefijo, $datosPrefijo);

    // Eliminar las claves usando executeRaw
    $keys = Redis::keys($prefijoToken . ':*');
    foreach ($keys as $key) {
      Redis::connection()->executeRaw(['DEL', $key]);
    }

    // Se agrega token opaco token opaco
    $tokenOpaco = TextoUtils::generarTokenOpaco();
    $tokenRedis = $prefijoToken . ':' . $tokenOpaco;

    // Se guarda token en redis
    if ($guardarToken) {
      Redis::set($tokenRedis, json_encode($objRedis), 'EX', $tiempoExpiracion);
    }

    return $tokenRedis;
  }
}
