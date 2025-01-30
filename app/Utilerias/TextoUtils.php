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

class TextoUtils
{
  /**
   * Metodo para obtener mensajes de clase validator
   * @param MessageBag $excepciones
   * @return string $mensajes
   */
  public static function obtenerMensajesValidator(MessageBag $excepciones)
  {
    $mensajes = "";

    foreach ($excepciones->all() as $excepcion)
      $mensajes .= $excepcion . '<br>';

    return $mensajes;
  }

  /**
   * Metodo para agregar log de error
   * @param Exception $e | Excepcion producida
   * @param string $clase | Clases y metodo del error
   */
  public static function agregarLogError(Exception $e, string $clase)
  {
    $p = $e->getPrevious();
    $codigo = $e->getCode();

    // Se valida que la excepcion no haya sido registrada y que provenga de clases locales
    if (empty($p) || $codigo != 300) {
      $lineaError   = $e->getLine();
      $mensajeError = $e->getMessage();
      Log::channel('errorlog')->error("{$clase}");
      Log::channel('errorlog')->error("Linea: {$lineaError}");
      Log::channel('errorlog')->error("Mensaje: {$mensajeError}");
      Log::channel('errorlog')->error("=================================================================================");
    }
  }

  /**
   * Método que retorna un string limpio de caracteres especiales
   * @param $cadena Texto a limpiar
   * @return false|string
   */
  public static function limpiarCadena($cadena)
  {
    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
    $cadena = mb_convert_encoding($cadena,  'ISO-8859-1', 'UTF-8');
    $cadena = strtr($cadena, mb_convert_encoding($originales,  'ISO-8859-1', 'UTF-8'), $modificadas);
    return mb_convert_encoding($cadena, 'UTF-8', 'ISO-8859-1');
  }

  /**
   * Método que retorna el hash sha512 de una cadena
   * @param string $cadena | Texto a hashear
   * @return string
   */
  public static function obtenerSha512(string $cadena)
  {
    return hash('sha512', $cadena);
  }

  // public static function generarUUIDv4()
  // {
  //   // Generar un UUIDv4
  //   $uuid = Uuid::uuid4();

  //   // Obtiene la representación en cadena del UUID
  //   $uuidString = $uuid->toString();

  //   return $uuidString;
  // }

  // public static function generarNanoId($lenght = 18, $caracteres = "-0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz")
  // {
  //   $client = new Client();

  //   return $client->formattedId($caracteres, $lenght);
  // }

  // public static function arrayKeysKebabACamelCase($kebabArray)
  // {
  //   $camelArray = [];
  //   foreach ($kebabArray as $kebabKey => $value) {
  //     // Convierte la llave de kebab case a camel case
  //     $camelKey = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $kebabKey))));
  //     $camelArray[$camelKey] = $value;
  //   }
  //   return $camelArray;
  // }

  /**
   * Método para validar si una cadena es un uuid bien formado
   * @param string $valor
   * @return bool $resultado
   */
  public static function esUUID($valor)
  {
    $resultado = false;

    $patron = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';
    $resultado = preg_match($patron, $valor);

    return $resultado == 1 ? true : false;
  }

  /**
   * Método para validar el tamaño de una cadena entre un minimo y maximo
   * @param int $minimo
   * @param int $maximo
   * @param string $cadena
   * @return bool $resultado
   */
  public static function validarLongitudEntre($minimo, $maximo, $cadena)
  {
    $resultado = false;
    $resultado = strlen($cadena) >= $minimo && strlen($cadena) <= $maximo;

    return $resultado;
  }

  /**
   * Metodo para agregar log de error
   * @param Exception $e | Excepcion producida
   * @param string $level | Nivel de error (error,debug,warning)
   * @param string $clase | Clases y metodo del error
   */
  public static function agregarLog(Throwable $e, $level, $codigoInterno)
  {
    $codigoExcepcion = $e->getCode();
    $lineaError   = $e->getLine();
    $mensajeError = $e->getMessage();
    $archivo = $e->getFile();

    Log::channel('errorlog')->{$level}("CodigoInterno: {$codigoInterno}");
    Log::channel('errorlog')->{$level}("Linea: {$lineaError}");
    Log::channel('errorlog')->{$level}("Archivo: {$archivo}");
    Log::channel('errorlog')->{$level}("CodigoExcepcion: {$codigoExcepcion}");
    if ($e == $e instanceof QueryException || $e == $e instanceof PDOException) {
      $mensajeDB = SQLResponse::obtenerMensaje($codigoExcepcion);
      Log::channel('errorlog')->{$level}("DB: $mensajeDB");
    }
    if ($level == 'error') {
      $stackTrace = $e->getTraceAsString();
      Log::channel('errorlog')->{$level}("Stacktrace: {$stackTrace}");
    }
    if ($e == $e instanceof DomainException) {
      $translations = include base_path('app/Translations/es_ES.php');
      $mensajeError = $translations[$mensajeError] ?? $mensajeError;
    }
    Log::channel('errorlog')->{$level}("Mensaje: {$mensajeError}");
    Log::channel('errorlog')->{$level}("=================================================================================");
    if ($level == 'debug' || $level == 'warning') {
      return $mensajeError;
    }
  }

  public static function generarNumeroAleatorio()
  {
    // Genera un número aleatorio de 6 dígitos entre 0 y 999999
    $numeroAleatorio = mt_rand(0, 999999);

    // Rellena con ceros a la izquierda si el número generado tiene menos de 6 dígitos
    $numeroAleatorio = str_pad($numeroAleatorio, 6, '0', STR_PAD_LEFT);

    return $numeroAleatorio;
  }

  public static function formatoDosDigitos($numero)
  {
    if ($numero < 10) {
      return '0' . $numero;
    } else {
      return (string) $numero;
    }
  }

  /**
   * Utileria que capitaliza la cadena mandada y si encuentra guiones bajos los transforma por espacios
   * @param string $cadena
   * @return string
   */
  public static function capitalizarTexto($cadena)
  {
    $cadenaConEspacios = str_replace('_', ' ', $cadena);

    return ucfirst($cadenaConEspacios);
  }

  /**
   * Metodo para generar token de session
   * @return string
   */
  public static function generarTokenOpaco()
  {
    $numeroRandom = random_int(1, 10000000);
    $sha          = hash('sha256', (string) $numeroRandom);
    $token        = substr($sha, -15);
    $token        = strtoupper($token);
    return $token;
  }

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
   * Utileria que genera un nano id
   * @param integer $lenght
   * @param string $caracteres
   * @return string
   */
  public static function generarNanoId(
    $lenght = 21,
    $caracteres = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"
  ) {
    $client = new Client();
    return $client->formattedId($caracteres, $lenght);
  }

  /**
   * Utileria que limpia cadena para que sea valida para nombre de archivo
   * @param string $texto
   * @return string
   */
  public static function limpiarNombreArchivo($texto)
  {
    // Reemplaza caracteres no válidos con una cadena vacía
    $texto = preg_replace('/[^a-zA-Z0-9-_]/', '', $texto);

    // Limitar longitud máxima (opcional, aquí se limita a 255 caracteres)
    $texto = substr($texto, 0, 255);

    // Reemplazar espacios por guiones bajos
    $texto = str_replace(' ', '_', $texto);

    // Convertir a minúsculas
    $texto = strtolower($texto);

    return $texto;
  }
}
