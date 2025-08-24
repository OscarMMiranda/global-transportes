<?php
session_start();
// 2) Modo depuración (solo DEV)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors',     1);
    ini_set('error_log',      __DIR__ . '/error_log.txt');

    // 3) Cargar config.php (define getConnection() y rutas)
    require_once __DIR__ . '/../../includes/config.php';

    // 4) Obtener la conexión
    $conn = getConnection();

// 1) Validar usuario autenticado (y rol si aplica)
if (!isset($_SESSION['usuario'])) {
    die("❌ Acceso no autorizado.");
}

// 2) Validar método POST y parámetro id
if ($_SERVER['REQUEST_METHOD'] !== 'POST' 
    || empty($_POST['id']) 
    || !is_numeric($_POST['id'])
) {
    die("❌ Solicitud inválida.");
}
$vehiculo_id = (int) $_POST['id'];

// 3) Comprobar que el vehículo exista y esté activo
$stmt = $conn->prepare(
    "SELECT placa 
       FROM vehiculos 
      WHERE id = ? 
        AND activo = 1"
);
$stmt->bind_param("i", $vehiculo_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("❌ Vehículo no encontrado o ya eliminado.");
}
$registro = $result->fetch_assoc();

// 4) Ejecutar actualización para marcar activo = 0
$stmt = $conn->prepare(
    "UPDATE vehiculos 
        SET activo = 0 
      WHERE id = ?"
);
if (!$stmt) {
    die("❌ Error al preparar actualización: " . $conn->error);
}
$stmt->bind_param("i", $vehiculo_id);
if (!$stmt->execute()) {
    die("❌ Error al ejecutar baja lógica: " . $stmt->error);
}

// 5) Flash message y redirección
$_SESSION['msg'] = "Vehículo “{$registro['placa']}” eliminado lógicamente.";
header("Location: vehiculos.php");
exit;
