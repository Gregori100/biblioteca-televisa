<?php

namespace App\Constantes;

class RedisConst
{
  const TTL_DEFAULT = 14400; // 4 Horas
  const TTL_5_MINUTOS = 300;
  const TTL_10_MINUTOS = 600;

  // Prefijo global

  // Tipos de prefijos
  const PREFIJO_GLOBAL_ARTE_GRAFICO = "arte-grafico:";
  const PREFIJO_AUTH_LOGIN_CLIENT   = "auth-login-client:";
  const PREFIJO_AUTH_SESSION_CLIENT = "auth-session-client:";
  const PREFIJO_EDITAR_CONTRATO_CLIENT = "editar-contrato-client:";
  const PREFIJO_AUTH_SESSION_USER   = "auth-session-user:";

  // Tipos de sufijos
  const SUFIJO_TOKEN_TUA            = "-token-tua";
}
