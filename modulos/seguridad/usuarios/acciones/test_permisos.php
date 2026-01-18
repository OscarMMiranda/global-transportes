<?php
// archivo: /modulos/seguridad/usuarios/acciones/test_permisos.php


ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

echo "INCLUDES_PATH = " . INCLUDES_PATH . "\n";

$permisosPath = INCLUDES_PATH . '/permisos.php';
echo "Probando cargar: $permisosPath\n";

if (!file_exists($permisosPath)) {
    die("ERROR: permisos.php NO existe.\n");
}

require_once $permisosPath;
echo "permisos.php cargado OK\n";

echo "Probando requirePermiso()...\n";

requirePermiso("usuarios", "ver");

echo "requirePermiso() ejecutado OK\n";