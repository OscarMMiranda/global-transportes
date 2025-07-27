<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/conexion.php';
$conn = getConexion();

if ($conn instanceof mysqli) {
    echo 'Conexión exitosa mysqli v' . $conn->server_info;
} else {
    echo 'Falló la instancia de mysqli';
}
