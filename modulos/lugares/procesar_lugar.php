<?php
session_start();
require_once '../../includes/conexion.php';

if (!isset($_POST['nombre'], $_POST['tipo_id'], $_POST['distrito_id'])) {
    die("❌ Error: Datos incompletos.");
}

$nombre = $conn->real_escape_string($_POST['nombre']);
$tipo_id = intval($_POST['tipo_id']);
$distrito_id = intval($_POST['distrito_id']);

$sql = "INSERT INTO lugares (nombre, tipo_id, distrito_id) VALUES ('$nombre', $tipo_id, $distrito_id)";

if ($conn->query($sql)) {
    header("Location: lugares.php"); // Redirigir de vuelta a la lista de lugares
} else {
    die("❌ Error al registrar el lugar: " . $conn->error);
}
?>
