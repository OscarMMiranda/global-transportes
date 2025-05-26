<?php
require_once '../../includes/conexion.php';

if (!isset($_GET['id'])) {
    die("❌ Error: ID de local no proporcionado.");
}

$id = intval($_GET['id']);
$sql = "DELETE FROM locales WHERE id = $id";

if ($conn->query($sql)) {
    header("Location: crear_local.php?lugar_id=" . $_GET['lugar_id']);
    exit();
} else {
    die("❌ Error al eliminar el local: " . $conn->error);
}
?>
