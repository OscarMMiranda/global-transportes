<?php
session_start();
require_once '../../includes/conexion.php';

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado.");
}

// Activar depuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

if (!isset($_POST['Nombre'], $_POST['tipo_id'], $_POST['Direccion'], $_POST['departamento_id'], $_POST['provincia_id'], $_POST['distrito_id'])) {
    die("❌ Error: Datos incompletos.");
}

// Obtener datos del formulario
$nombre = $conn->real_escape_string($_POST['Nombre']);
$tipo_id = intval($_POST['tipo_id']);
$direccion = $conn->real_escape_string($_POST['Direccion']);
$departamento_id = intval($_POST['departamento_id']);
$provincia_id = intval($_POST['provincia_id']);
$distrito_id = intval($_POST['distrito_id']);

// Insertar datos en la base de datos
$sql = "INSERT INTO lugares (nombre, tipo_id, direccion, departamento_id, provincia_id, distrito_id) VALUES ('$nombre', $tipo_id, '$direccion', $departamento_id, $provincia_id, $distrito_id)";

if ($conn->query($sql)) {
    echo "✅ Lugar registrado con éxito.";
    header("Location: lugares.php"); // Redirigir a la lista de lugares
    exit();
} else {
    die("❌ Error al registrar el lugar: " . $conn->error);
}

?>
