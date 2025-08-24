<?php
// acciones/guardar.php

require_once __DIR__ . '/../modelo.php';
require_once __DIR__ . '/../validaciones.php';

// 1) Obtener datos del formulario
$conductorId = $_POST['conductor_id'] ?? null;
$tractoId    = $_POST['vehiculo_tracto_id'] ?? null;
$remolqueId  = $_POST['vehiculo_remolque_id'] ?? null;
$fechaInicio = $_POST['fecha_inicio'] ?? null;
$fechaFin    = $_POST['fecha_fin'] ?? null;
$estadoId    = getEstadoId($conn, 'activo');

// 2) Validaciones clave
if (!validarCategoriaVehiculo($conn, $tractoId, 1)) {
    die("Error: El vehículo tracto no tiene la categoría correcta.");
}

if (!validarCategoriaVehiculo($conn, $remolqueId, 2)) {
    die("Error: El vehículo remolque no tiene la categoría correcta.");
}

if (!validarFechasAsignacion($fechaInicio, $fechaFin)) {
    die("Error: Fechas inválidas.");
}

// 3) Insertar en BD
if (insertAsignacion($conn, $conductorId, $tractoId, $remolqueId, $fechaInicio, $fechaFin, $estadoId)) {
    header("Location: index.php?action=list");
    exit;
} else {
    die("Error al guardar la asignación.");
}
