<?php
session_start();
require_once '../../includes/conexion.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log');

echo "✅ Depuración iniciada.<br>";


if (!$conn) {
    die("❌ Error de conexión a la base de datos: " . mysqli_connect_error());
} else {
    echo "✅ Conexión a la base de datos establecida correctamente.";
}

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado. Solo los administradores pueden acceder a esta página.");
} else {
    echo "✅ Usuario autenticado como administrador.";
}

// Obtener lista de conductores activos
$sql_conductores = "SELECT id, nombres, apellidos FROM conductores WHERE activo = 1";
$result_conductores = $conn->query($sql_conductores);
if (!$result_conductores) {
    die("❌ Error al obtener la lista de conductores: " . $conn->error);
} else {
    echo "✅ Lista de conductores obtenida correctamente.";
}

// Obtener lista de vehículos activos
$sql_vehiculos = "SELECT id, placa, modelo FROM vehiculos WHERE activo = 1";
$result_vehiculos = $conn->query($sql_vehiculos);
if (!$result_vehiculos) {
    die("❌ Error al obtener la lista de vehículos: " . $conn->error);
} else {
    echo "✅ Lista de vehículos obtenida correctamente.";
}


// Obtener el estado_id correspondiente a "activo"
$sql_estado_activo = "SELECT id FROM estado_asignacion WHERE nombre = 'activo'";
$result_estado_activo = $conn->query($sql_estado_activo);
$row_estado_activo = $result_estado_activo ? $result_estado_activo->fetch_assoc() : null;
$estado_id_activo = $row_estado_activo['id'] ?? null;

if (!$estado_id_activo) {
    die("❌ Error: No se encontró el estado 'activo' en la tabla estado_asignacion.");
} else {
    echo "✅ `estado_id` para 'activo' obtenido correctamente: " . $estado_id_activo;
}


?>
