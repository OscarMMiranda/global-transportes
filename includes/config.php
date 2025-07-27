<?php
// includes/config.php

// declare(strict_types=1);

// 1. Iniciar sesión (una sola vez)
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
	}

// 2. Constantes de rutas
	define('BASE_PATH', realpath(__DIR__ . '/..'));       // Raíz del proyecto
	define('INCLUDES_PATH', BASE_PATH . '/includes');     // Carpeta includes
	define('PUBLIC_PATH', BASE_PATH . '/public_html');    // Carpeta pública
	define('BASE_URL', '/');                              // Ajusta si tu ERP está en subcarpeta

// 3. Zona horaria y reporting
	date_default_timezone_set('America/Lima');
	error_reporting(E_ALL);
	ini_set('display_errors', 0); // 1 en desarrollo, 0 en producción

// 4. Cargar la conexión y obtener $conn
	require_once INCLUDES_PATH . '/conexion.php';
	// $conn = getConnection();      // Función definida en conexion.php


// 1. Ruta absoluta a /includes
if (! defined('INCLUDES_PATH')) {
    define('INCLUDES_PATH', __DIR__);
}

// 2. Ruta pública (ajusta 'public_html' si tu carpeta se llama distinto)
if (! defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/public_html'));
}


