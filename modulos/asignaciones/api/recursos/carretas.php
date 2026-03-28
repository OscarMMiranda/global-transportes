<?php
// archivo: /modulos/asignaciones/api/recursos/carretas.php

require_once __DIR__ . '/../../../../includes/config.php';
require_once __DIR__ . '/../../model/carretas.php';

$conn = getConnection();

$data = obtenerCarretasTodos($conn);

// Normalizar
foreach ($data as &$row) {
    if (!isset($row['placa'])) {
        if (isset($row['carreta'])) {
            $row['placa'] = $row['carreta'];
        } elseif (isset($row['vehiculo'])) {
            $row['placa'] = $row['vehiculo'];
        }
    }
}

echo json_encode($data);
