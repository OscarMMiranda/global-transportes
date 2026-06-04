<?php
// archivo: modulos/asignaciones/api/filtros.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../model/asignaciones.php';

$conn = getConnection();

// Filtros actuales
$filtros = array(
    'conductor' => isset($_GET['conductor']) ? $_GET['conductor'] : '',
    'tracto'    => isset($_GET['tracto']) ? $_GET['tracto'] : '',
    'carreta'   => isset($_GET['carreta']) ? $_GET['carreta'] : '',
    'estado'    => isset($_GET['estado']) ? $_GET['estado'] : ''
);

$data = array(
    'conductores' => obtenerConductoresFiltrados($conn, $filtros),
    'tractos'     => obtenerTractosFiltrados($conn, $filtros),
    'carretas'    => obtenerCarretasFiltradas($conn, $filtros),
    'estados'     => obtenerEstadosFiltrados($conn, $filtros)
);

echo json_encode($data);
