<?php
require_once '../../includes/conexion.php';
session_start();

// Activar depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// Verificar sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado.");
}

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que los campos requeridos no estén vacíos
    if (empty($_POST['placa']) || empty($_POST['tipo_id']) || empty($_POST['marca_id']) || empty($_POST['modelo']) || empty($_POST['anio']) || empty($_POST['empresa_id'])) {
        die("<p style='color: red;'>❌ Todos los campos obligatorios deben estar completos.</p>");
    }

    // Convertir texto a mayúsculas, excepto Observaciones
    $placa = strtoupper(trim($_POST['placa']));
    $tipo_id = intval($_POST['tipo_id']);
    $marca_id = intval($_POST['marca_id']);
    $modelo = strtoupper(trim($_POST['modelo']));
    $anio = intval($_POST['anio']);
    $empresa_id = intval($_POST['empresa_id']);
    $observaciones = trim($_POST['observaciones']); // No se convierte a mayúsculas

    // Insertar en la base de datos
    $sql = "INSERT INTO vehiculos (placa, tipo_id, marca_id, modelo, anio, empresa_id, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siisiss", $placa, $tipo_id, $marca_id, $modelo, $anio, $empresa_id, $observaciones);

    if ($stmt->execute()) 
        {
        echo "<p style='color: green;'>✅ Vehículo registrado exitosamente.</p>";
        header("Refresh:3; url=vehiculos.php");
        exit();
        } 
    else 
        {
        echo "<p style='color: red;'>❌ Error al registrar el vehículo: " . $stmt->error . "</p>";
        }
}
?>
