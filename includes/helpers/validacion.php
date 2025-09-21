<?php
// Archivo: /includes/helpers/validacion.php

/**
 * Verifica si un recurso externo está accesible (HTTP 200)
 * @param string $url
 * @return bool
 */
function verificarRecurso($url) {
    $headers = @get_headers($url);
    return $headers && strpos($headers[0], '200') !== false;
}

/**
 * Verifica si un archivo local existe y es legible
 * @param string $ruta
 * @return bool
 */
function verificarArchivoLocal($ruta) {
    return file_exists($ruta) && is_readable($ruta);
}

/**
 * Verifica si un parámetro existe en $_GET o $_POST
 * @param string $clave
 * @param string $origen ('GET' o 'POST')
 * @return mixed|null
 */
function obtenerParametro($clave, $origen = 'POST') {
    $fuente = strtoupper($origen) === 'GET' ? $_GET : $_POST;
    return isset($fuente[$clave]) ? trim($fuente[$clave]) : null;
}

/**
 * Registra un mensaje en el log de errores personalizado
 * @param string $mensaje
 * @param string $archivoLog
 */
function registrarError($mensaje, $archivoLog = __DIR__ . '/../../logs/error_log.txt') {
    $timestamp = date('Y-m-d H:i:s');
    error_log("[$timestamp] $mensaje\n", 3, $archivoLog);
}

/**
 * Valida si una URL es segura (HTTPS y dominio permitido)
 * @param string $url
 * @param array $dominiosPermitidos
 * @return bool
 */
function esURLSegura($url, $dominiosPermitidos = []) {
    $parsed = parse_url($url);
    if (!isset($parsed['scheme']) || strtolower($parsed['scheme']) !== 'https') return false;
    if (!isset($parsed['host'])) return false;
    return empty($dominiosPermitidos) || in_array($parsed['host'], $dominiosPermitidos);
}