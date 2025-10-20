<?php
// archivo: /modulos/mantenimiento/entidades/index.php
// Router modular con trazabilidad total y control de errores

// 🛡️ 1. Modo depuración (solo en entorno DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// 🧩 2. Cargar configuración base
$configPath = __DIR__ . '/../../../includes/config.php';
if (!is_file($configPath)) {
    error_log("❌ Configuración no encontrada: $configPath");
    die("<div style='padding:20px;font-family:sans-serif;color:#c00;'>Error interno: configuración no disponible.</div>");
}
require_once $configPath;

// 🔌 3. Obtener conexión
$conn = getConnection();
if (!($conn instanceof mysqli)) {
    error_log("❌ Conexión fallida en index.php desde IP: " . $_SERVER['REMOTE_ADDR'] . " a las " . date('Y-m-d H:i:s'));
    die("<div style='padding:20px;font-family:sans-serif;color:#c00;'>Error de conexión con la base de datos.</div>");
}

// 🧭 4. Determinar acción solicitada
$action = isset($_GET['action']) ? strtolower(trim($_GET['action'])) : 'list';

// 📦 5. Mapeo de acciones permitidas
$acciones = array(
    'list'    => 'ListController.php',
    'form'    => 'FormController.php',
    'delete'  => 'DeleteController.php',
    'restore' => 'RestoreController.php',
    'trash'   => 'TrashController.php',
    'api'     => 'ApiController.php',
    'view'    => 'VistaEntidadController.php' // 🆕 Acción para ficha completa
);

// 🔍 6. Validación y trazabilidad
if (!array_key_exists($action, $acciones)) {
    error_log("⚠️ Acción no permitida: $action");
    header("HTTP/1.0 404 Not Found");
    echo "<div style='padding:20px;font-family:sans-serif;color:#c00;'>Acción no válida: <strong>$action</strong></div>";
    exit;
}

error_log("📌 Acción ejecutada: $action");

// 🚀 7. Cargar controlador correspondiente
$controlador = __DIR__ . "/controllers/" . $acciones[$action];
if (is_file($controlador)) {
    require_once $controlador;
} else {
    error_log("❌ Controlador no encontrado: $controlador");
    header("HTTP/1.0 500 Internal Server Error");
    echo "<div style='padding:20px;font-family:sans-serif;color:#c00;'>Error interno: controlador no disponible.</div>";
}