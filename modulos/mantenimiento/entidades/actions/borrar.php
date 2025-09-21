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
if (!is_object($conn) || get_class($conn) !== 'mysqli') {
    error_log("❌ getConnection() no devolvió un objeto mysqli");
    header("Location: ../controllers/ListController.php?error=conexion");
    ob_end_flush();
    exit;
}

if ($conn->connect_errno) {
    error_log("❌ Error de conexión: " . $conn->connect_error);
    header("Location: ../controllers/ListController.php?error=conexion");
    ob_end_flush();
    exit;
}

// 3) Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: /modulos/sistema/login.php");
    ob_end_flush();
    exit;
}

// 4) Validar ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<div class='alert alert-danger'><i class='fa fa-ban'></i> ID inválido.</div>";
    ob_end_flush();
    exit;
}

// 5) Borrado lógico (soft delete)
$sql = "UPDATE entidades SET estado = 'inactivo', fecha_modificacion = NOW() WHERE id = $id LIMIT 1";
$res = $conn->query($sql);

if ($res && $conn->affected_rows === 1) {
    header("Location: ../controllers/ListController.php?borrado=ok");
    ob_end_flush();
    exit;
} else {
    echo "<div class='alert alert-danger'><strong>Error:</strong> No se pudo marcar como inactivo.</div>";
    echo "<pre>Consulta ejecutada:\n" . $sql . "\nError: " . $conn->error . "</pre>";
    ob_end_flush();
}
?>