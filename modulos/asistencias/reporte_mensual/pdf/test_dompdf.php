<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Probando ruta Dompdf...<br>";

$path = __DIR__ . "/dompdf/autoload.inc.php";
echo "Ruta: $path<br>";

if (!file_exists($path)) {
    die("NO EXISTE DOMPDF");
}

echo "Dompdf encontrado. Intentando cargar...<br>";

require_once $path;

echo "Dompdf cargado correctamente.";
