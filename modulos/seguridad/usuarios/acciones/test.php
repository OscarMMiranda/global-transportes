<?php
// archivo: /modulos/seguridad/usuarios/acciones/test.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "DOCUMENT_ROOT = " . $_SERVER['DOCUMENT_ROOT'] . "\n";

$path = $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
echo "Probando cargar: $path\n";

if (file_exists($path)) {
    echo "config.php SÍ existe\n";
} else {
    echo "config.php NO existe\n";
}

echo "\nListando carpeta actual:\n";
print_r(scandir(__DIR__));