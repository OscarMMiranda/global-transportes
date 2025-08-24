<?php
// includes/database.php

/**
 * Carga la configuración y la función de conexión,
 * invoca getConnection() y devuelve la instancia de mysqli.
 */

// 1) Cargar config.php (inicia sesión, errores y carga conexion.php)
require_once __DIR__ . '/../../../../includes/config.php';


// 2) Intentar conexión
try {
    $db = getConnection();
} catch (Exception $e) {
    // Manejo de error: log y redirección a página amigable
    error_log('DB Error: ' . $e->getMessage());
    header('Location: ' . BASE_URL . 'error.php');
    exit;
}

// 3) Devolver la conexión al archivo que lo incluya
return $db;
