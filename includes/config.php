<?php
// includes/config.php
// includes/config.php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
define('INCLUDES_PATH', __DIR__);
require_once INCLUDES_PATH . '/conexion.php';
// 1. Ruta absoluta a /includes
if (! defined('INCLUDES_PATH')) {
    define('INCLUDES_PATH', __DIR__);
}

// 2. Ruta pública (ajusta 'public_html' si tu carpeta se llama distinto)
if (! defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/public_html'));
}

// 3. Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 4. Cargar conexión
require_once INCLUDES_PATH . '/conexion.php';

// (Opcional) Autoloaders, constantes extra, configuración de timezone…

// Ruta base de la app (desde la raíz del dominio)
// Si tu ERP vive directo en “public_html”, deja: '/'
// Si está en subcarpeta '/erp/', pon '/erp/'
define('BASE_URL', '/');
