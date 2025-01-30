<?php

namespace App\Constantes;

class ArchivoConst
{
  // Rutas de carpetas de storage
  const RUTA_STORAGE_S3_ARTICULOS = 'articulos/';
  const RUTA_STORAGE_S3_USUARIOS  = 'usuarios/';
  const RUTA_STORAGE_S3_PAQUETES  = 'paquetes/';
  const RUTA_STORAGE_S3_MODELOS   = 'modelos/';
  const RUTA_STORAGE_S3_ARTICULOS_PAQUETE = 'articulos-paquete/';
  const RUTA_STORAGE_S3_CONTRATOS_GRUPALES = 'contratos-grupales/';

  // RUTAS ENDPOITS API
  const RUTA_API_ARCHIVOS = "thumbs/archivos";

  // NOMBRE CARPETA TEMPORAL
  const NOMBRE_CARPETA_TEMPORAL = "temp";
}
