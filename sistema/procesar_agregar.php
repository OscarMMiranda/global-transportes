<?php
session_start();
require_once '../includes/conexion.php';

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado.");
}

// Validar datos recibidos
if (!isset($_POST['tabla']) || !isset($_POST['datos'])) {
    die("❌ Error: No hay datos para insertar.");
}

$tabla = htmlspecialchars($_POST['tabla']);
$datos = $_POST['datos'];

// Construir la consulta SQL para insertar datos
$columnas = implode(", ", array_keys($datos));
$valores = "'" . implode("', '", array_map([$conn, 'real_escape_string'], array_values($datos))) . "'";

$sql = "INSERT INTO `$tabla` ($columnas) VALUES ($valores)";
$resultado = $conn->query($sql);

if (!$resultado) {
    die("❌ Error en consulta SQL: " . $conn->error);
}

// Redirigir con mensaje de éxito
header("Location: agregar_registro.php?tabla=$tabla&mensaje=Registro agregado correctamente");
exit();
?>
