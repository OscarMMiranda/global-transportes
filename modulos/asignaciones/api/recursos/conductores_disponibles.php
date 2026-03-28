<?php
// archivo: /modulos/asignaciones/api/recursos/conductores_disponibles.php

require_once __DIR__ . '/../../../../includes/config.php';
require_once __DIR__ . '/../../model/conductores.php';

$conn = getConnection();

$data = obtenerConductoresDisponibles($conn);

// Normalizar para frontend
foreach ($data as &$row) {
    if (isset($row['conductor'])) {
        $row['nombre'] = $row['conductor'];
    } elseif (isset($row['nombres'])) {
        $row['nombre'] = $row['nombres'];
    } elseif (isset($row['nombre_completo'])) {
        $row['nombre'] = $row['nombre_completo'];
    }
}

echo json_encode($data);
