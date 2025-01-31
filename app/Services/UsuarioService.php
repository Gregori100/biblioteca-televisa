<?php

namespace App\Services;

use App\Constantes\ArchivoConst;
use App\Constantes\RedisConst;
use App\Constantes\TipoArchivoConst;
use App\Constantes\UsuarioConst;
use App\Objects\ArchivoSubirS3;
use App\Objects\PerfilObj;
use App\Objects\UsuarioObj;
use App\Repositories\Action\UsuarioRepoAction;
use App\Repositories\Data\UsuarioRepoData;
use App\Services\BO\RedisBO;
use App\Services\BO\UsuarioBO;
use App\Utilerias\HashUtils;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Redis;
use stdClass;

class UsuarioService
{
  /**
   * Listar usuarios
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
    $usuarios = UsuarioRepoData::listar($columnas, $filtros, $limit, $offset, $order);

    foreach ($usuarios as &$usuario) {
      $usuario->hash_id = HashUtils::getHash($usuario->usuario_id);
    }

    return $usuarios;
  }

  /**
   * Listar perfiles de usuarios
   * @param string $columnas
   * @param array $filtros
   * @param int $limit
   * @param int $offset
   * @param array $order
   * @return array
   */
  public static function listarPerfiles(
    $columnas = '',
    $filtros = [],
    $limit = null,
    $offset = null,
    $order = []
  ) {
    $perfiles = UsuarioRepoData::listarPerfiles($columnas, $filtros, $limit, $offset, $order);

    foreach ($perfiles as &$perfil) {
      $perfil->hash_id = HashUtils::getHash($perfil->perfil_id);
    }

    return $perfiles;
  }

  /**
   * Listar rel_usuarios_perfiles
   * @param string $columnas
   * @param array $filtros
   * @param int $limit
   * @param int $offset
   * @param array $order
   * @return array
   */
  public static function listarRelUsuariosPerfiles(
    $columnas = '',
    $filtros = [],
    $limit = null,
    $offset = null,
    $order = []
  ) {
    return UsuarioRepoData::listarRelUsuariosPerfiles($columnas, $filtros, $limit, $offset, $order);
  }

  /**
   * Método que autentica un usuario
   * @param string $usuario
   * @param string $password
   * @return stdClass
   */
  public static function autenticarUsuario($usuario, $password)
  {
    $usuarioObj = UsuarioRepoData::validarUsuario($usuario, $password);

    if (empty($usuarioObj)) {
      throw new AuthenticationException("Las credenciales no coinciden con ningún usuario registrado");
    }

    return $usuarioObj;
  }

  /**
   * Método para eliminar sesiones relacionadas a un usuario
   * @param string $prefijoToken | Prefijo de token a buscar: auth-session-nombreusuario
   * @return void
   * @throws Exception
   */
  public static function borrarSesionesRelacionadas($prefijoToken)
  {
    // Elimina todas las sesiones relacionadas en Redis
    $keys = Redis::keys($prefijoToken . ':*');

    // Eliminar las claves usando executeRaw
    foreach ($keys as $key) {
      Redis::connection()->executeRaw(['DEL', $key]);
    }

    $keys = Redis::keys($prefijoToken . ':*');
  }

  /**
   * Método que guarda token
   * @param string $token
   * @param object $usuario
   * @return void
   */
  public static function guardarToken($token, $usuario)
  {
    $usuario->password = '';
    // Guarda el token en Redis con un tiempo de expiración
    Redis::set($token, json_encode($usuario), 'EX', RedisConst::TTL_DEFAULT); // Expira en 4 horas
  }

  /**
   * Método que obtiene un objeto de perfil de usuario
   * @param string $perfilId
   * @param bool $validarStatus
   * @param bool $obtenerObj | Variable que devuelve indica si devuelve el objeto en lugar del registro
   * @return ContratoGrupalObj
   */
  public static function obtenerPerfilUsuario($perfilId, $validarStatus = true, $obtenerObj = false)
  {
    $registroPerfil =  self::listarPerfiles("", ["perfilId" => $perfilId]);

    if (empty($registroPerfil)) {
      throw new Exception("No se pudo obtener registro del perfil de usuario.", 300);
    }

    $registroPerfilObj = $registroPerfil[0];

    if ($validarStatus && $registroPerfilObj->status != UsuarioConst::PERFIL_USUARIO_STATUS_ACTIVO) {
      throw new Exception("El perfil de usuario ha cambiado de status, favor de recargar la página.", 300);
    }

    if ($obtenerObj) {
      $perfilObj = new PerfilObj();
      $perfilObj->inicializarDesdeObjeto($registroPerfilObj);
      return $perfilObj;
    }

    return $registroPerfilObj;
  }

  /**
   * Service que agrega un usuario
   * @param array $datos
   * @return void
   */
  public static function agregar(array $datos)
  {
    // Validación de usuario registrado
    self::validarAgregarUsuario($datos);

    $insert = UsuarioBO::armarInsertUsuario($datos);
    return UsuarioRepoAction::agregar($insert);
  }

  /**
   * Service que agrega la relacion de un usuario con un perfil
   * @param array $datos
   * @return void
   */
  public static function agregarRelacionPerfilUsuario(array $datos)
  {
    $insert = UsuarioBO::armarInsertPerfilUsuario($datos);
    UsuarioRepoAction::agregarRelPerfilUsuario($insert);
  }

  /**
   * Service que agrega la relación de un usuario con sucursales
   * @param array $datos
   * @return void
   */
  public static function agregarRelacionSucursalUsuario(array $datos)
  {
    $insert = UsuarioBO::armarInsertSucursalUsuario($datos);
    UsuarioRepoAction::agregarRelSucursalUsuario($insert);
  }

  /**
   * Método que obtiene un objeto de usuario
   * @param string $usuarioId
   * @param bool $validarStatus
   * @return UsuarioObj
   */
  public static function obtenerUsuario($usuarioId, $validarStatus = true)
  {
    $registroUsuario =  self::listar("", ["usuarioId" => $usuarioId]);

    if (empty($registroUsuario)) {
      throw new Exception("No se pudo obtener registro del usuario.", 300);
    }

    $usuarioObj = new UsuarioObj();
    $usuarioObj->inicializarDesdeObjeto($registroUsuario[0]);

    if ($validarStatus && $usuarioObj->getStatus() != UsuarioConst::USUARIO_STATUS_ACTIVO) {
      throw new Exception("El usuario seleccionado ha cambiado de status, favor de recargar la página.", 300);
    }

    return $usuarioObj;
  }

  /**
   * Método que actualiza el status de la relacion de un perfil con usuario
   * @param string $relUsuarioPerfilId
   * @param string $usuarioId
   * @param string $status
   * @return void
   */
  public static function actualizarStatusRelPefilUsuario($relUsuarioPerfilId, $usuarioId, $status)
  {
    $update = UsuarioBO::armarUpdateStatusRelPefilUsuario($usuarioId, $status);
    UsuarioRepoAction::editarRelPefilUsuario($update, $relUsuarioPerfilId);
  }

  /**
   * Service que edita un usuario
   * @param array $datos
   * @return void
   */
  public static function editar(array $datos)
  {
    $update = UsuarioBO::armarUpdateUsuario($datos);
    UsuarioRepoAction::editar($update, $datos["usuarioEditadoId"]);
  }

  /**
   * Listar rel_usuarios_sucursales
   * @param string $columnas
   * @param array $filtros
   * @param int $limit
   * @param int $offset
   * @param array $order
   * @return array
   */
  public static function listarRelUsuariosSucursales(
    $columnas = '',
    $filtros = [],
    $limit = null,
    $offset = null,
    $order = []
  ) {
    return UsuarioRepoData::listarRelUsuariosSucursales($columnas, $filtros, $limit, $offset, $order);
  }

  /**
   * Service que edita el password a un usuario
   * @param array $datos
   * @return void
   */
  public static function editarPassword(array $datos)
  {
    // Validación de usuario
    self::obtenerUsuario($datos["usuarioEditadoId"]);

    // Update a usuario
    $update = UsuarioBO::armarUpdatePasswordUsuario($datos);
    UsuarioRepoAction::editar($update, $datos["usuarioEditadoId"]);

    // Actualizar usuario en caso de que exista en redis
    self::actualizarObjetoUsuarioRedis($datos["usuarioEditadoId"]);
  }

  /**
   * Service que elimina a un usuario
   * @param array $datos
   * @return void
   */
  public static function eliminar(array $datos)
  {
    // Validación de usuario
    self::obtenerUsuario($datos["usuarioEditadoId"]);

    // Update a usuario
    $update = UsuarioBO::armarUpdateUsuario($datos, false);
    UsuarioRepoAction::editar($update, $datos["usuarioEditadoId"]);

    // Se elimina llave de redis en caso de que exista
    RedisService::guardarKeyRedis(RedisConst::PREFIJO_AUTH_SESSION_USER, ["usuarioId" => $datos["usuarioEditadoId"]]);
  }

  /**
   * Service que agrega un usuario
   * @param array $datos
   * @return string
   */
  public static function agregarPerfil(array $datos)
  {
    // Validación de calve de perfil
    self::validarAgregarPerfil($datos);

    $insert = UsuarioBO::armarInsertPerfil($datos);
    return UsuarioRepoAction::agregarPerfil($insert);
  }

  /**
   * Service que elimina a un perfil de acceso
   * @param array $datos
   * @return void
   */
  public static function eliminarPerfil(array $datos)
  {
    // Update a usuario
    $update = UsuarioBO::armarUpdateStatusPefilUsuario($datos);
    UsuarioRepoAction::editarPerfil($update, $datos["perfilId"]);
  }

  /**
   * Service que genera la relación de sucursales con usuarios
   * @param array $datos
   * @param string $sucursalId
   * @param bool $eliminarRel
   * @return void
   */
  public static function updateRelacionSucursalUsuario(array $datos, string $sucursalId, bool $eliminarRel = false)
  {
    $update = UsuarioBO::armarUpdateRelSucursalUsuario($datos, $eliminarRel);
    UsuarioRepoAction::editarRelSucursalUsuario($update, $datos["usuarioEditadoId"], $sucursalId);
  }

  /**
   * Service que edita a un perfil de acceso
   * @param array $datos
   * @return void
   */
  public static function editarPerfil(array $datos)
  {
    // Update a usuario
    $update = UsuarioBO::armarUpdatePefilUsuario($datos);
    UsuarioRepoAction::editarPerfil($update, $datos["perfilId"]);
  }

  /**
   * Método que edita los permisos en usuarios activos, cuando se edita los permisos
   * de un perfil
   * @param string $perfilId
   * @return void
   */
  public static function editarPermisosPorPerfilEnUsuariosActivos($perfilId)
  {
    // Modificar los objetos usuarios de redis que tienen relacion con el perfil
    $keys = Redis::keys(RedisConst::PREFIJO_AUTH_SESSION_USER . '*');

    $relaciones = UsuarioService::listarRelUsuariosPerfiles("", [
      "perfilId"  => $perfilId,
      "status"    => [UsuarioConst::REL_PERFIL_USUARIO_ACTIVO],
    ]);
    $usuariosIds = array_map(fn($relacion) => $relacion->usuario_id, $relaciones);

    // Se limpia el prefijo del proyecto
    $keys = RedisBO::limpiarPrefijoKeys($keys);

    foreach ($keys as $key) {
      preg_match('/auth-session-user:(\d+):/', $key, $matches);

      if (in_array($matches[1], $usuariosIds)) {
        $usuarioObj = UsuarioService::listar("", ["usuarioId" => $matches[1]])[0];
        $usuarioObj->permisos = json_encode(array_map(function ($permiso) {
          return $permiso->codigo;
        }, PermisoService::listarPorUsuario($matches[1])));

        // Se guarda token en redis
        Redis::setex($key, RedisConst::TTL_DEFAULT, json_encode($usuarioObj));
      }
    }
  }

  /**
   * Método que actualiza el objeto de redis de un usuario, debido a alguna actualizacion
   * @param string $usuarioEditadoId
   * @return void
   */
  public static function actualizarObjetoUsuarioRedis($usuarioEditadoId)
  {
    // Se edita el usuario en caso de que exista en redis
    $prefijoToken = RedisBO::armarPrefijoRedis(RedisConst::PREFIJO_AUTH_SESSION_USER, ["usuarioId" => $usuarioEditadoId]);

    // Obtencion de llave
    $keys = Redis::keys($prefijoToken . ':*');

    $usuarioActualizadoObj = self::listar("", ["usuarioId" => $usuarioEditadoId])[0];
    $usuarioActualizadoObj->permisos = json_encode(array_map(function ($permiso) {
      return $permiso->codigo;
    }, PermisoService::listarPorUsuario($usuarioEditadoId)));

    // Se limpia el prefijo del proyecto
    $keys = RedisBO::limpiarPrefijoKeys($keys);

    foreach ($keys as $key) {
      // Se sobre escribe token en redis
      Redis::setex($key, RedisConst::TTL_DEFAULT, json_encode($usuarioActualizadoObj));
    }
  }

  /**
   * Método que sube documento de usuario a s3
   * @param array $datos[base64,nombreImagen]
   * @param UsuarioObj $usuarioObj
   */
  public static function subirImagenUsuarioS3($datos, $usuarioObj)
  {
    $base64Image = $datos["base64"];
    // $extension   = pathinfo($datos["nombreImagen"], PATHINFO_EXTENSION);
    $extension   = "png";

    $documentoData = base64_decode($base64Image);

    $tempFilePath = tempnam(sys_get_temp_dir(), 'usuario-imagen');
    file_put_contents($tempFilePath, $documentoData);

    // Preparar el nombre del archivo para S3
    $nombreArchivo = $usuarioObj->getUsuarioId() . "/" .  $datos["nombreSistema"] . "." . $extension;

    // Crear instancia de ArchivoSubirS3 y subir archivo a S3
    $archivo = new ArchivoSubirS3($tempFilePath, null, TipoArchivoConst::USUARIOS, $nombreArchivo);
    StorageService::subirSinCarpetaFecha([$archivo]);

    unlink($tempFilePath);
  }

  /********************************************************************/
  /*************************** VALIDACIONES ***************************/
  /********************************************************************/

  private static function validarAgregarUsuario($datos)
  {
    $registrosUsuarios = self::listar("", [
      "usuario" => $datos["usuario"],
      "status"  => [UsuarioConst::USUARIO_STATUS_ACTIVO],
    ]);

    if (!empty($registrosUsuarios)) {
      throw new Exception("El usuario ya se encuentra registrado, favor de revisar. {$datos["usuario"]}", 300);
    }
  }

  private static function validarAgregarPerfil($datos)
  {
    $registrosPerfiles = self::listarPerfiles("", ["clave" => $datos["clave"]]);

    if (!empty($registrosPerfiles)) {
      throw new Exception("La clave de perfil de acceso ya se encuentra registrada, favor de revisar. {$datos["clave"]}", 300);
    }
  }
}
