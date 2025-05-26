<?php
session_start();
require_once '../includes/conexion.php';

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado.");
}

// Validar datos recibidos
if (!isset($_POST['tabla']) || !isset($_POST['datos'])) {
    die("❌ Error: No hay datos para actualizar.");
}

$tabla = htmlspecialchars($_POST['tabla']);
$datos = $_POST['datos'];

// Procesar cada registro
foreach ($datos as $id => $columnas) {
    $set_sql = [];
    foreach ($columnas as $campo => $valor) {
        $set_sql[] = "`$campo`='" . $conn->real_escape_string($valor) . "'";
    }
    $sql = "UPDATE `$tabla` SET " . implode(", ", $set_sql) . " WHERE id='$id'";
    $conn->query($sql);
}

// Redirigir con mensaje de éxito
header("Location: editar_registros.php?tabla=$tabla&mensaje=Registros actualizados correctamente");
exit();
?>
