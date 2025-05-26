<?php
require_once '../../includes/conexion.php';

if (!isset($_POST['lugar_id'], $_POST['nombre'], $_POST['direccion'], $_POST['distrito_id'])) {
    die("❌ Error: Datos incompletos.");
}

$lugar_id = intval($_POST['lugar_id']);
$distrito_id = intval($_POST['distrito_id']);
$nombre = $conn->real_escape_string($_POST['nombre']);
$direccion = $conn->real_escape_string($_POST['direccion']);

// Verificar que el distrito existe en la base de datos
$distritoExiste = $conn->query("SELECT id FROM distritos WHERE id = $distrito_id");
if ($distritoExiste->num_rows === 0) {
    die("❌ Error: El distrito seleccionado no es válido.");
}

// Insertar el local con su distrito asignado
$sql = "INSERT INTO locales (lugar_id, nombre, direccion, distrito_id) VALUES ($lugar_id, '$nombre', '$direccion', $distrito_id)";

if ($conn->query($sql)) {
    header("Location: crear_local.php?lugar_id=$lugar_id");
    exit();
} else {
    die("❌ Error al registrar el local: " . $conn->error);
}
?>
