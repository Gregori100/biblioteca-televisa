<?php

namespace App\Utilerias;

use App\Constantes\NumeroConst;

class NumeroUtils
{
  /**
   * Utileria que obtiene el descuento de un precio
   * @param string $cadena
   * @return float
   */
  public static function precioConDescuento($precio, $descuento)
  {
    return $precio - ($precio * $descuento / 100);
  }

  /**
   * Método que realiza operaciones genéricas
   * @param string $operacion | Que operación se desea realizar
   * @param mixed $num1
   * @param mixed $num2
   * @param int $decimales | Número de decimales que se quiere para la operación
   */
  public static function calculadoraGenerica($operacion = NumeroConst::OPERACION_SUMA, $num1 = 1, $num2 = 1, $decimales = 2)
  {
    $resultado = 0;
    switch ($operacion) {
      case NumeroConst::OPERACION_SUMA:
        $resultado = round(bcadd(strval($num1), strval($num2), $decimales), $decimales);
        break;
      case NumeroConst::OPERACION_RESTA:
        $resultado = round(bcsub(strval($num1), strval($num2), $decimales), $decimales);
        break;
      case NumeroConst::OPERACION_MULTIPLIACION:
        $resultado = round(bcmul(strval($num1), strval($num2), $decimales), $decimales);
        break;
      case NumeroConst::OPERACION_DIVISION:
        $resultado = round(bcdiv(strval($num1), strval($num2), $decimales), $decimales);
        break;
      default:
        $resultado = round(bcadd(strval($num1), strval($num2), $decimales), $decimales);
        break;
    }

    return $resultado;
  }
}
