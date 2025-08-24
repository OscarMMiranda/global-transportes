<?php
// eliminar.php
// Script para procesar la eliminación (soft-delete) de un Tipo de Vehículo

// 1) Mostrar errores en desarrollo  
ini_set('display_errors', 1);  
ini_set('display_startup_errors', 1);  
error_reporting(E_ALL);

// 2) Iniciar sesión y cargar configuración  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/conexion.php';

$conn = getConnection();
if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

// 3) Cargar el controlador  
require_once __DIR__ . '/controller.php';
$ctrl = new TipoVehiculoController($conn);

// 4) Validar y obtener el ID a eliminar  
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php?error=' . urlencode('ID inválido'));
    exit;
}

// 5) Ejecutar la eliminación  
try {
    $ctrl->delete($id);
    // El delete() ya lanza header('Location: ...') y exit, así que
    // si llegas aquí tu delete() no redirigió. Forzamos una:
    header('Location: index.php?msg=' . urlencode('eliminado'));
} catch (Exception $e) {
    // Capturar cualquier excepción y redirigir con el mensaje
    header('Location: index.php?error=' . urlencode($e->getMessage()));
}
exit;
