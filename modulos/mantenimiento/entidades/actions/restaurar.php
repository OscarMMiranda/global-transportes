<?php
ob_start(); // ← inicia el buffer de salida
session_start();

// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

$conn = getConnection();
if (!$conn || !($conn instanceof mysqli)) {
    echo "<div class='alert alert-danger'><i class='fa fa-ban'></i> Conexión fallida.</div>";
    ob_end_flush(); // ← libera el buffer antes de salir
    exit;
}

// 3) Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: /modulos/sistema/login.php");
    ob_end_flush();
    exit();
}

// 4) Validar ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<div class='alert alert-danger'><i class='fa fa-ban'></i> ID inválido.</div>";
    ob_end_flush();
    exit;
}

// 5) Restaurar entidad (cambiar estado a activo)
$sql = "UPDATE entidades SET estado = 'activo', fecha_modificacion = NOW() WHERE id = $id LIMIT 1";
$res = $conn->query($sql);

if ($res && $conn->affected_rows === 1) {
    header("Location: ../controllers/ListController.php");
    ob_end_flush();
    exit;
} else {
    echo "<div class='alert alert-danger'><strong>Error:</strong> No se pudo restaurar la entidad.</div>";
    echo "<pre>Consulta ejecutada:\n" . $sql . "\nError: " . $conn->error . "</pre>";
    ob_end_flush();
}
?>