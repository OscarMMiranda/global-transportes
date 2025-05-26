<?php
session_start();
require_once '../../includes/conexion.php';

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
