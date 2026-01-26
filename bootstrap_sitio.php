<?php
// archivo : /bootstrap_sitio.php

// 1. Cargar configuración global (ya incluye sesión y conexión)
require_once __DIR__ . '/includes/config.php';

// 2. Activar modo debug (solo en desarrollo)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', BASE_PATH . '/error_log.txt'); // usa ruta absoluta

// 3. Cargar helpers globales
require_once INCLUDES_PATH . '/funciones.php';

// 4. Obtener conexión (ya está disponible vía getConnection())
$conn = getConnection();

// 5. Definir constantes útiles del sitio (si no están definidas)
if (!defined('SITE_NAME'))     define('SITE_NAME', 'Global Transportes');
if (!defined('SITE_VERSION')) define('SITE_VERSION', '1.0');

// 6. Registrar visita pública (opcional y trazable)
//    IMPORTANTE: Solo registrar si $accion y $modulo existen.
//    Esto evita romper endpoints AJAX como guardar.php, obtener.php, etc.

if (!empty($_SERVER['REQUEST_URI'])) {

    $uri     = $_SERVER['REQUEST_URI'];
    $ip      = obtenerIP();
    $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'visitante';

    // Solo registrar si las variables existen y no están vacías
    if (isset($accion) && isset($modulo) && !empty($accion) && !empty($modulo)) {
        registrarEnHistorial($conn, $usuario, $accion, $modulo, $ip);
    }

    // Si NO existen, simplemente no registrar nada.
    // Esto evita errores como:
    // "Undefined variable: accion" y "parámetros incompletos"
}

// 7. Generar token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
}
