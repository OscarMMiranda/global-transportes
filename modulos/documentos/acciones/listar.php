<?php
// archivo: /modulos/documentos/acciones/listar.php

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once __DIR__ . '/../../../includes/config.php';

$conn = getConnection();
if (!$conn) {
    echo json_encode(["data" => []]);
    exit;
}

// Consulta ajustada a tu BD real
$sql = "
    SELECT 
        d.id,
        d.entidad_tipo,
        d.entidad_id,
        d.tipo_documento_id,
        d.numero,
        d.fecha_inicio,
        d.fecha_vencimiento,
        d.estado
    FROM documentos d
    ORDER BY d.id DESC
";

$result = $conn->query($sql);

$rows = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {

        // Estado
        $estado = ($row['estado'] == 1)
            ? '<span class=\"badge bg-success\">Vigente</span>'
            : '<span class=\"badge bg-danger\">Vencido</span>';

        // Botones
        $acciones = '
            <button class=\"btn btn-sm btn-primary btn-ver\" data-id=\"'.$row['id'].'\">
                <i class=\"fa fa-eye\"></i>
            </button>
            <button class=\"btn btn-sm btn-warning btn-editar\" data-id=\"'.$row['id'].'\">
                <i class=\"fa fa-edit\"></i>
            </button>
            <button class=\"btn btn-sm btn-danger btn-eliminar\" data-id=\"'.$row['id'].'\">
                <i class=\"fa fa-trash\"></i>
            </button>
        ';

        $rows[] = [
            $row['id'],
            $row['entidad_tipo'],
            $row['entidad_id'],
            $row['tipo_documento_id'],
            $row['numero'],
            $row['fecha_inicio'],
            $row['fecha_vencimiento'],
            $estado,
            $acciones
        ];
    }
}

echo json_encode(["data" => $rows]);
exit;
