<?php

namespace App\Utilerias;

use DomainException;
use Exception;
use Hidehalo\Nanoid\Client;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use PDOException;
use Ramsey\Uuid\Uuid;
use Throwable;

class ArrayUtils
{
  /**
   * Utileria que convierte una cadena separada por comas a un array
   * @param string $cadena
   * @return array
   */
  public static function cambiarTextoArray($cadena)
  {
    return explode(',', $cadena);
  }

  /**
   * Método que funciona como array_find de javascript
   * @param array $array
   * @param function $callback
   * @param mixed
   */
  public static function arrayFind($array, $callback)
  {
    foreach ($array as $element) {
      if ($callback($element)) {
        return $element;
      }
    }
    return null;
  }
}
