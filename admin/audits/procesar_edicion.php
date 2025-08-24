<?php
session_start();

// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../includes/config.php';

$conn = getConnection();

// 2) Cargar conexión y helpers

require_once __DIR__ . '/../../includes/helpers.php';

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado.");
}

// Validar datos recibidos
if (!isset($_POST['tabla']) || !isset($_POST['datos'])) {
    die("❌ Error: No hay datos para actualizar.");
}

$tabla = htmlspecialchars($_POST['tabla']);
$datos = $_POST['datos'];

// Procesar cada registro
foreach ($datos as $id => $columnas) {
    $set_sql = [];
    foreach ($columnas as $campo => $valor) {
        $set_sql[] = "`$campo`='" . $conn->real_escape_string($valor) . "'";
    }
    $sql = "UPDATE `$tabla` SET " . implode(", ", $set_sql) . " WHERE id='$id'";
    $conn->query($sql);
}

// Redirigir con mensaje de éxito
header("Location: editar_registros.php?tabla=$tabla&mensaje=Registros actualizados correctamente");
exit();
?>
