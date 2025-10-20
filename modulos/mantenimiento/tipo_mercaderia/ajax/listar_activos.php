<?php
// archivo: /modulos/mantenimiento/tipo_mercaderia/ajax/listar_activos.php

// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../../../includes/config.php';
$conn = getConnection();

// 3) Validación defensiva
if (!isset($conn) || !($conn instanceof mysqli)) {
  echo "<div class='alert alert-danger'>❌ Error de conexión con la base de datos.</div>";
  exit;
}

// 4) Incluir tabla modularizada
include __DIR__ . '/../componentes/tabla_activos.php';