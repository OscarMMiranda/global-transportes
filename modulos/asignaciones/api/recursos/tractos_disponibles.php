<?php
// archivo: /modulos/asignaciones/api/recursos/tractos_disponibles.php

require_once __DIR__ . '/../../../../includes/config.php';
require_once __DIR__ . '/../../model/tractos.php';

$conn = getConnection();

$data = obtenerTractosDisponibles($conn);

// Normalizar
foreach ($data as &$row) {
    if (!isset($row['placa'])) {
        if (isset($row['vehiculo'])) {
            $row['placa'] = $row['vehiculo'];
        } elseif (isset($row['codigo'])) {
            $row['placa'] = $row['codigo'];
        }
    }
}

echo json_encode($data);
