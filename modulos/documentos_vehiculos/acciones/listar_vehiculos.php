<?php
// archivo: /modulos/documentos_vehiculos/acciones/listar_vehiculos.php

require_once __DIR__ . '/../../../includes/config.php';

$conn = getConnection();

header('Content-Type: application/json');

/**
 * Determina si un vehículo está ACTIVO o DESHABILITADO
 * Compatible con PHP 5.6
 */
function estadoVehiculo($vehiculo_id, $conn)
{
    $sql = "
        SELECT estado, fecha_vencimiento
        FROM documentos
        WHERE entidad_tipo = 'vehiculo'
        AND entidad_id = $vehiculo_id
    ";

    $res = $conn->query($sql);

    // Si no tiene documentos → deshabilitado
    if (!$res || $res->num_rows == 0) {
        return "<span class='badge bg-danger'>DESHABILITADO</span>";
    }

    $activo = true;

    while ($doc = $res->fetch_assoc()) {

        // Documento no activo
        if ($doc['estado'] != 'activo') {
            $activo = false;
            break;
        }

        // Documento vencido
        if (!empty($doc['fecha_vencimiento'])) {
            if (strtotime($doc['fecha_vencimiento']) < time()) {
                $activo = false;
                break;
            }
        }
    }

    if ($activo) {
        return "<span class='badge bg-success'>ACTIVO</span>";
    }

    return "<span class='badge bg-danger'>DESHABILITADO</span>";
}



$sql = 
    "SELECT 
        v.id,
        v.placa,
        v.anio,
        m.nombre AS marca,
        (
            SELECT COUNT(*) 
            FROM documentos d 
            WHERE d.entidad_tipo = 'vehiculo' 
            AND d.entidad_id = v.id
        ) AS total_documentos,
        (
            SELECT COUNT(*) 
            FROM documentos d 
            WHERE d.entidad_tipo = 'vehiculo' 
            AND d.entidad_id = v.id
            AND d.fecha_vencimiento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
        ) AS por_vencer
    FROM vehiculos v
    LEFT JOIN marca_vehiculo m ON m.id = v.marca_id
    WHERE v.activo = 1
    ORDER BY v.placa ASC
";

$res = $conn->query($sql);

$data = [];

while ($row = $res->fetch_assoc()) {

    $estado = estadoVehiculo($row['id'], $conn);

    $acciones = '
        <a href="/modulos/documentos_vehiculos/vistas/documentos.php?id='.$row['id'].'" 
           class="btn btn-primary btn-sm">
           Ver documentos
        </a>
    ';

    $data[] = [
        "placa" => $row['placa'],
        "marca" => $row['marca'],
        "anio" => $row['anio'],
        "total_documentos" => $row['total_documentos'],
        "por_vencer" => $row['por_vencer'],
        "estado" => $estado, // NUEVO
        "acciones" => $acciones
    ];
}

echo json_encode(["data" => $data]);
exit;
