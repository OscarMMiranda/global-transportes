<?php
// archivo: /includes/debug.php

// Activador global (podés moverlo a config.php si querés)
define('DEBUG_MODE', true); // Cambiar a false en producción

/**
 * Muestra trazabilidad visual en pantalla (solo si DEBUG_MODE está activo)
 */
function logStep($mensaje) {
    if (DEBUG_MODE) {
        echo "<div class='alert alert-info small mb-1'>🔍 $mensaje</div>";
    }
}

/**
 * Registra trazabilidad en consola del navegador
 */
function logConsole($mensaje) {
    if (DEBUG_MODE) {
        echo "<script>console.log('[DEBUG] " . addslashes($mensaje) . "');</script>";
    }
}

/**
 * Registra trazabilidad en archivo de log
 */
function logFile($mensaje, $archivo = 'debug.log') {
    if (DEBUG_MODE) {
        $registro = date('Y-m-d H:i:s') . " | $mensaje\n";
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/logs/$archivo", $registro, FILE_APPEND);
    }
}

/**
 * Auditoría básica de acceso (IP, navegador)
 */
function logAccess($contexto = 'index.php') {
    $log = date('Y-m-d H:i:s') . " | $contexto | IP: " . $_SERVER['REMOTE_ADDR'] . " | UA: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/logs/accesos.log', $log, FILE_APPEND);
}