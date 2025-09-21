<?php
session_start();

// 🔧 Configuración de logs para depuración local
ini_set('error_log', __DIR__ . '/error_log.txt');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// 1) Cargar configuración, utilidades y modelo
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once __DIR__ . '/../includes/funciones.php';
require_once __DIR__ . '/../modelo.php';

// 2) Inicializar conexión y sesión
$conn = getConnection();
validarSesionAdmin();

// 3) Validar método y entrada
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("[DELETE] Método inválido: " . $_SERVER['REQUEST_METHOD']);
    exit("❌ Método no permitido.");
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if (!validarId($id)) {
    error_log("[DELETE] ID inválido o no recibido.");
    exit("❌ ID inválido.");
}

// 4) Ejecutar soft delete
$usuarioId = $_SESSION['usuario_id'];
$ipOrigen  = obtenerIP();
error_log("[DELETE] ID recibido: {$id}, usuarioId: {$usuarioId}, IP: {$ipOrigen}");

$response = [
    'success' => false,
    'message' => 'Error al eliminar el vehículo.'
];

if (eliminarVehiculo($conn, $id, $usuarioId, $ipOrigen)) {
    registrarVehiculoHistorial($conn, $id, $usuarioId, 'Eliminado');
    registrarEnHistorial($_SESSION['usuario'], "Eliminó vehículo ID {$id}", 'vehiculos', $ipOrigen);
    $response['success'] = true;
    $response['message'] = 'Vehículo eliminado correctamente.';
    error_log("[DELETE] Vehículo ID={$id} marcado como inactivo.");
} else {
    error_log("[DELETE] Falló eliminación de vehículo ID={$id}");
}

// 5) Redirigir con mensaje
	$_SESSION['msg'] = $response['message'];
		header("Location: ../controlador.php?action=list");
	exit;