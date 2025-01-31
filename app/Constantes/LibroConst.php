<?php

namespace App\Constantes;

class LibroConst
{
  // Folio inicial
  const LIBRO_FOLIO_INICIAL    = 10001;

  // Status
  const LIBRO_STATUS_ACTIVO    = 200;
  const LIBRO_STATUS_ELIMINADO = 300;

  // Status disponibilidad
  const LIBRO_STATUS_DISPONIBILIDAD_DISPONIBLE = "DISPONIBLE";
  const LIBRO_STATUS_DISPONIBILIDAD_OCUPADO    = "OCUPADO";
  const LIBRO_STATUS_DISPONIBILIDAD_RETIRADO   = "RETIRADO";
}
