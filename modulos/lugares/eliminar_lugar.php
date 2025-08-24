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



if (!isset($_GET['id'])) {
    die("❌ Error: ID de lugar no proporcionado.");
}

$id = intval($_GET['id']);
$sql = "DELETE FROM lugares WHERE id = $id";

if ($conn->query($sql)) {
    header("Location: lugares.php");
} else {
    die("❌ Error al eliminar el lugar: " . $conn->error);
}
?>
