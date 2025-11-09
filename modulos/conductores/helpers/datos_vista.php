<?php
// archivo: helpers/datos_vista.php

require_once __DIR__ . '/../controllers/conductores_controller.php';

/**
 * Carga todos los conductores activos (o todos si se activa el modo "mostrar inactivos")
 */
$mostrarInactivos = isset($_GET['inactivos']) && $_GET['inactivos'] == '1';
$conductores = listarConductores($conn, $mostrarInactivos);

/**
 * Carga zonas si los conductores están asociados a zonas (opcional)
 */
if (function_exists('listarZonas')) {
    $zonas = listarZonas();
}

/**
 * Carga tipos de licencia si están definidos en el sistema (opcional)
 */
if (function_exists('listarTiposLicencia')) {
    $tiposLicencia = listarTiposLicencia();
}