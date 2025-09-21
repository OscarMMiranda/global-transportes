<?php
// modulos/vehiculos/bootstrap.php

// 1. Sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Debug (solo en DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// 3. Configuración global
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

// 4. Helpers del módulo Vehículos
require_once __DIR__ . '/includes/funciones.php';

// 5. Validación de sesión/rol
validarSesionAdmin();

// 6. Conexión y datos de usuario/IP
$conn       = getConnection();
$usuarioId  = obtenerUsuarioId();
$ipOrigen   = obtenerIP();