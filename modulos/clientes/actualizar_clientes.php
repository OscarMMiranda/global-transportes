<?php
require_once '../../includes/conexion.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar conexión
if (!$conn) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}
