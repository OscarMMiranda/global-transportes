<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../includes/config.php';

if (!isset($conn)) {
    die('❌ $conn no está definido');
}

if (!($conn instanceof mysqli)) {
    die('❌ $conn no es una instancia de mysqli');
}

$res = $conn->query("SELECT 1 AS ok");
if (!$res) {
    die('❌ Error en query: ' . $conn->error);
}

$row = $res->fetch_assoc();
echo '✅ Conexión OK: ' . json_encode($row);
