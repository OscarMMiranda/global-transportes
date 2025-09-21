<?php
ob_start();
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

if (!is_object($conn) || get_class($conn) !== 'mysqli') {
    header("Location: ../controllers/ListController.php?error=conexion");
    ob_end_flush();
    exit;
}

if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: /modulos/sistema/login.php");
    ob_end_flush();
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: ../controllers/ListController.php?error=id");
    ob_end_flush();
    exit;
}

// Borrado físico
$sql = "DELETE FROM entidades WHERE id = $id LIMIT 1";
$res = $conn->query($sql);

if ($res && $conn->affected_rows === 1) {
    header("Location: ../controllers/ListController.php?eliminado=ok");
} else {
    header("Location: ../controllers/ListController.php?error=eliminacion");
}
ob_end_flush();
exit;
?>