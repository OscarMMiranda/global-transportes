<?php
// archivo: restaurar.php — cambia estado a “activo”

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID inválido');
}

$id = intval($_GET['id']);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

if (!($conn instanceof mysqli)) {
    die('Error de conexión');
}

$sql = "UPDATE entidades SET estado = 'activo' WHERE id = $id LIMIT 1";
$res = $conn->query($sql);

if ($res && $conn->affected_rows > 0) {
    header('Location: ../controllers/ListController.php');
    exit;
} else {
    die('No se pudo restaurar la entidad');
}