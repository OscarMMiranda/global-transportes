<?php
require_once '../../includes/conexion.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


// Convertir texto a mayúsculas excepto Observaciones
$placa = strtoupper(trim($_POST['placa']));
$tipo_id = intval($_POST['tipo_id']);
$marca_id = intval($_POST['marca_id']);
$estado_id = intval($_POST['estado_id']);
$configuracion_id = 1;
$modelo = strtoupper(trim($_POST['modelo']));
$anio = intval($_POST['anio']);
$empresa_id = intval($_POST['empresa_id']);
$observaciones = trim($_POST['observaciones']);

// Intentar la inserción en la base de datos
$sql = "INSERT INTO vehiculos (placa, tipo_id, marca_id, estado_id, configuracion_id, modelo, anio, empresa_id, observaciones) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Verificar si la consulta se preparó correctamente
if (!$stmt) {
    die("❌ Error preparando la consulta: " . $conn->error);
}

$stmt->bind_param("siiissssi", $placa, $tipo_id, $marca_id, $estado_id, $configuracion_id, $modelo, $anio, $empresa_id, $observaciones);

// Intentar ejecutar la consulta
if ($stmt->execute())
{
    echo "<p style='color: green;'>✅ Registro exitoso en la base de datos.</p>";
    header("Refresh:3; url=vehiculos.php");
    exit();
} 
else {
    echo "<p style='color: red;'>❌ Error al insertar el vehículo: " . $stmt->error . "</p>";
}
// Finalizar el script para ver resultado antes del historial
exit();


?>

