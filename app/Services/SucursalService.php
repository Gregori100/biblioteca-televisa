<?php

namespace App\Services;

use App\Constantes\SucursalConst;
use App\Constantes\UsuarioConst;
use App\Objects\SucursalObj;
use App\Repositories\Action\SucursalRepoAction;
use App\Repositories\Data\SucursalRepoData;
use App\Services\BO\SucursalBO;
use App\Utilerias\HashUtils;
use Exception;

class SucursalService
{
  /**
   * Listar sucursales
   *
   * @param string $columnas
   * @param array $filtros
   * @param int $limit
   * @param int $offset
   * @param array $order
   * @return array
   */
  public static function listar(
    $columnas = '',
    $filtros = [],
    $limit = null,
    $offset = null,
    $order = []
  ) {
    $sucursales = SucursalRepoData::listar($columnas, $filtros, $limit, $offset, $order);

    foreach ($sucursales as &$sucursal) {
      $sucursal->hash_id = HashUtils::getHash($sucursal->sucursal_id);
    }

    return $sucursales;
  }

  /**
   * Service que agrega una sucursales
   * @param array $datos
   * @return string
   */
  public static function agregar(array $datos)
  {
    // Validación
    self::validarAgregarSucursal($datos);

    $insert = SucursalBO::armarInsertSucursal($datos);
    return SucursalRepoAction::agregar($insert);
  }

  /**
   * Service que edita una sucursales
   * @param array $datos
   * @return void
   */
  public static function editar(array $datos)
  {
    // Validación
    self::validarEditarSucursal($datos, true);

    $update = SucursalBO::armarUpdateSucursal($datos, true);
    SucursalRepoAction::editar($update, $datos["sucursalId"]);
  }

  /**
   * Service que edita una sucursales
   * @param array $datos
   * @return void
   */
  public static function eliminar(array $datos)
  {
    // Validación
    self::validarEditarSucursal($datos);

    $update = SucursalBO::armarUpdateSucursal($datos);
    SucursalRepoAction::editar($update, $datos["sucursalId"]);
  }

  /**
   * Método que obtiene un objeto de sucursal
   * @param string $sucursalId
   * @param bool $validarStatus
   * @return SucursalObj
   */
  public static function obtenerSucursal($sucursalId, $validarStatus = true)
  {
    $registroSucursal =  self::listar("", ["sucursalId" => $sucursalId]);

    if (empty($registroSucursal)) {
      throw new Exception("No se pudo obtener registro de la sucursal.", 300);
    }

    $sucursalObj = new SucursalObj();
    $sucursalObj->inicializarDesdeObjeto($registroSucursal[0]);

    if ($validarStatus && $sucursalObj->getStatus() != SucursalConst::SUCURSAL_STATUS_ACTIVO) {
      throw new Exception("El usuario seleccionado ha cambiado de status, favor de recargar la página.", 300);
    }

    return $sucursalObj;
  }

  /********************************************************************/
  /*************************** VALIDACIONES ***************************/
  /********************************************************************/

  private static function validarAgregarSucursal($datos)
  {
    $registrosSucursales = self::listar("", ["clave" => $datos["clave"]]);

    if (!empty($registrosSucursales)) {
      throw new Exception("Ya existe alguna sucursal con esta clave, favor de revisar. {$datos["clave"]}", 300);
    }
  }

  /**
   * Método privado que valida la edición de una sucursal
   * @param array $datos
   * @param boolean $esEdit
   * @return void
   */
  private static function validarEditarSucursal($datos, $esEdit = false)
  {
    $registrosSucursal = self::listar("", ["sucursalId" => $datos["sucursalId"]]);

    if (empty($registrosSucursal)) {
      throw new Exception("No se encontró registro de la sucursal", 300);
    }
    $sucursalObj = $registrosSucursal[0];

    if ($sucursalObj->status == SucursalConst::SUCURSAL_STATUS_ELIMINADO) {
      throw new Exception("El status de la sucursal a cambiado. Favor de recargar la página", 300);
    }

    if (!$esEdit && $sucursalObj->default) {
      throw new Exception("No se puede eliminar la sucursal ya que es la sucursal default.", 300);
    }

    if(!$esEdit){
      $registrosRelUsuariosSucursales = UsuarioService::listarRelUsuariosSucursales("", [
        "sucursalId"     => $datos["sucursalId"],
        "status"         => [UsuarioConst::REL_SUCURSAL_USUARIO_ACTIVO],
        "statusUsuario"  => [UsuarioConst::USUARIO_STATUS_ACTIVO],
      ]);

      if (!empty($registrosRelUsuariosSucursales)) {
        throw new Exception("No se puede eliminar la sucursal ya que es la sucursal esta relacionada a algún usuario activo.", 300);
      }
    }
  }
}
