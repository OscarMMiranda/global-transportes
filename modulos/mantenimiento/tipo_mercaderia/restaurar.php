<?php

session_start();

// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../../includes/config.php';
// require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();



// Validar llegada por POST con un ID
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    $_SESSION['error'] = "Solicitud inválida para restaurar.";
    header("Location: index.php");
    exit;
}

$id = (int) $_POST['id'];

// Restaurar cambiando estado a 1
$stmt = $conn->prepare("
  UPDATE tipos_mercaderia
     SET estado = 1
   WHERE id = ?
");
if (!$stmt) {
    $_SESSION['error'] = "Error al preparar restauración: " . $conn->error;
    header("Location: index.php");
    exit;
}

$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    $_SESSION['msg'] = "restaurado";
} else {
    $_SESSION['error'] = "Error al restaurar: " . $stmt->error;
}

header("Location: index.php");
exit;
