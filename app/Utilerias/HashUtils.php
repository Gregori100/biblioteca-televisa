<?php

namespace App\Utilerias;

use App\Exceptions\BusinessException;
use Carbon\Carbon;
use DomainException;
use Exception;
use Hidehalo\Nanoid\Client;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use PDOException;
use Ramsey\Uuid\Uuid;
use Throwable;

class HashUtils
{
  /**
   * Utileria que genera un hash en base a una cadena
   * @param string $cadena
   * @return string
   */
  public static function getHash(string $cadena): string
  {
    $longitud = 5;
    $hash = hash('sha256', $cadena);

    $hashCorto = substr($hash, strlen($hash) - $longitud, $longitud);

    return $hashCorto;
  }

  /**
   * Utileria que valida el hash
   * @param string $cadena
   * @return string
   */
  public static function validarHash($id, $hash)
  {
    if (empty($id) || empty($hash)) {
      throw new BusinessException("La URL es incorrecta para acceder");
    } else {
      if (self::getHash($id) != $hash) {
        throw new BusinessException("La URL es incorrecta para acceder");
      }
    }
  }

  /**
   * Utileria que arma una cadena en un hashKey
   * @param string $cadena
   * @return string
   */
  public static function armarHashKey($cadena){
    $timestamp = floor(Carbon::now()->timestamp / 600);
    $dataWithTimestamp = $cadena . $timestamp;
    $dataWithTimestampUtf8 = mb_convert_encoding($dataWithTimestamp, 'UTF-8');
    $hash = hash('sha256', $dataWithTimestampUtf8);
    return substr($hash, -10);
  }
}
