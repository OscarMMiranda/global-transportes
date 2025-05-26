<?php
require_once '../../includes/conexion.php';

if (!isset($_POST['id'], $_POST['nombre'], $_POST['direccion'], $_POST['distrito_id'])) {
    die("❌ Error: Datos incompletos.");
}

$id = intval($_POST['id']);
$nombre = $conn->real_escape_string($_POST['nombre']);
$direccion = $conn->real_escape_string($_POST['direccion']);
$distrito_id = intval($_POST['distrito_id']);

$sql = "UPDATE locales SET nombre = '$nombre', direccion = '$direccion', distrito_id = $distrito_id WHERE id = $id";

if ($conn->query($sql)) {
    header("Location: editar_local.php?id=$id");
    exit();
} else {
    die("❌ Error al actualizar el local: " . $conn->error);
}
?>
