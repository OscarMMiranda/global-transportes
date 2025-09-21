<?php
// archivo: /modulos/mantenimiento/entidades/index.php

// 🛡️ 1. Modo depuración (solo en entorno DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// 🧩 2. Cargar configuración base
require_once __DIR__ . '/../../../includes/config.php';

// 🔌 3. Obtener conexión
$conn = getConnection();
if (!($conn instanceof mysqli)) {
    error_log("❌ Conexión fallida en index.php");
    die("Error de conexión con la base de datos.");
}

// 🧭 4. Determinar acción solicitada (sin operador ??)
$action = isset($_GET['action']) ? strtolower(trim($_GET['action'])) : 'list';

// 📦 5. Mapeo de acciones permitidas
$acciones = array(
    'list'    => 'ListController.php',
    'form'    => 'FormController.php',
    'delete'  => 'DeleteController.php',
    'restore' => 'RestoreController.php',
    'trash'   => 'TrashController.php',
    'api'     => 'ApiController.php'
);

// 🔍 6. Validación y trazabilidad
if (!array_key_exists($action, $acciones)) {
    error_log("⚠️ Acción no permitida: " . $action);
    header("HTTP/1.0 404 Not Found");
    echo "Acción no válida.";
    exit;
}

error_log("📌 Acción ejecutada: " . $action);

// 🚀 7. Cargar controlador correspondiente
$controlador = __DIR__ . "/controllers/" . $acciones[$action];
if (file_exists($controlador)) {
    require_once $controlador;
} else {
    error_log("❌ Controlador no encontrado: " . $controlador);
    header("HTTP/1.0 500 Internal Server Error");
    echo "Error interno: controlador no disponible.";
}