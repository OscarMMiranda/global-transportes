<?php
// archivo: /modulos/clientes/index.php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// 1) Configuración general
require_once __DIR__ . '/../../includes/config.php';
$conn = getConnection();

// =========================
// 2) Lista blanca corporativa
// =========================
$allowedActions = array('list', 'form', 'delete', 'trash', 'restore', 'api');

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

if (!in_array($action, $allowedActions, true)) {
    header("HTTP/1.0 404 Not Found");
    exit("Acción no válida.");
}

// =========================
// 3) Si es API → NO cargar header, NO cargar footer
// =========================
if ($action === 'api') {
    require __DIR__ . '/controllers/ApiController.php';
    exit; // ← OBLIGATORIO: evita mezclar HTML + JSON
}

// =========================
// 4) Cargar header corporativo del módulo
// =========================
require_once __DIR__ . '/componentes/header.php';

// =========================
// 5) Router corporativo
// =========================
$controller = __DIR__ . '/controllers/' . ucfirst($action) . 'Controller.php';

if (!file_exists($controller)) {
    header("HTTP/1.0 500 Internal Server Error");
    exit("Controlador no encontrado: " . $controller);
}

require $controller;
