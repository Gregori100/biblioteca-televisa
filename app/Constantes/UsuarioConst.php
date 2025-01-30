<?php

namespace App\Constantes;

class UsuarioConst
{
  const USUARIO_STATUS_ACTIVO    = 200;
  const USUARIO_STATUS_ELIMINADO = 300;

  // Status global
  const USUARIO_GLOBAL    = true;
  const USUARIO_NO_GLOBAL = false;

  // Perfil usuario
  const PERFIL_USUARIO_STATUS_ACTIVO    = 200;
  const PERFIL_USUARIO_STATUS_ELIMINADO = 300;

  // Perfil global
  const PERFIL_GLOBAL    = true;
  const PERFIL_NO_GLOBAL = false;

  // Rel Perfil Usuario
  const REL_PERFIL_USUARIO_ACTIVO   = 200;
  const REL_PERFIL_USUARIO_INACTIVO = 300;

  // Rel Sucursal Usuario
  const REL_SUCURSAL_USUARIO_ACTIVO   = 200;
  const REL_SUCURSAL_USUARIO_INACTIVO = 300;

  // Status fotografias usuario
  const USUARIO_FOTOGRAFIA_STATUS_ACTIVO    = 200;
  const USUARIO_FOTOGRAFIA_STATUS_ELIMINADO = 300;
}
