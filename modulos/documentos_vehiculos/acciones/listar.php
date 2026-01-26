<?php
// archivo: /modulos/documentos_vehiculos/acciones/listar.php

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

$sql = "
    SELECT 
        d.id,
        d.entidad_id AS vehiculo_id,
        v.placa,
        td.nombre AS tipo_documento,
        d.numero,
        d.fecha_inicio,
        d.fecha_vencimiento,
        d.estado
    FROM documentos d
    INNER JOIN vehiculos v ON v.id = d.entidad_id
    INNER JOIN tipos_documento td ON td.id = d.tipo_documento_id
    WHERE d.entidad_tipo = 'vehiculo'
    ORDER BY d.id DESC
";

$res = $conn->query($sql);

$rows = [];

if ($res) {
    while ($row = $res->fetch_assoc()) {

        $estado = ($row['estado'] == 1)
            ? '<span class="badge bg-success">Vigente</span>'
            : '<span class="badge bg-danger">Vencido</span>';

        $acciones = '
            <button class="btn btn-sm btn-primary btn-ver" data-id="'.$row['id'].'">
                <i class="fa fa-eye"></i>
            </button>
            <button class="btn btn-sm btn-warning btn-editar" data-id="'.$row['id'].'">
                <i class="fa fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-danger btn-eliminar" data-id="'.$row['id'].'">
                <i class="fa fa-trash"></i>
            </button>
        ';

        $rows[] = [
            "id" => $row['id'],
            "placa" => $row['placa'],
            "tipo_documento" => $row['tipo_documento'],
            "numero" => $row['numero'],
            "fecha_inicio" => $row['fecha_inicio'],
            "fecha_vencimiento" => $row['fecha_vencimiento'],
            "estado" => $estado,
            "acciones" => $acciones
        ];
    }
}

echo json_encode(["data" => $rows]);
exit;
