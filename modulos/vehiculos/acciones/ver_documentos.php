<?php
// archivo: /modulos/vehiculos/acciones/ver_documentos.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$id = intval($_POST['id']);

$sql = "
    SELECT 
        d.id,
        d.tipo_documento_id,
        td.nombre AS tipo_documento,
        d.numero,
        d.fecha_inicio,
        d.fecha_vencimiento,
        d.archivo,
        d.uploaded_by,
        d.observaciones,
        d.created_at,
        u.usuario AS usuario_nombre
    FROM documentos d
    LEFT JOIN tipos_documento td ON td.id = d.tipo_documento_id
    LEFT JOIN usuarios u ON u.id = d.uploaded_by
    WHERE d.entidad_tipo = 'vehiculo'
      AND d.entidad_id = $id
      AND d.eliminado = 0
      AND d.is_current = 1
    ORDER BY d.fecha_vencimiento ASC
";

$res = $conn->query($sql);

if (!$res) {
    error_log("SQL ERROR: " . $conn->error);
    echo json_encode([
        "success" => false,
        "html" => "<div class='text-danger'>Error SQL: " . $conn->error . "</div>"
    ]);
    exit;
}

$rows = "";
$hoy = date("Y-m-d");

while ($d = $res->fetch_assoc()) {

    if ($d['fecha_vencimiento'] < $hoy) {
        $estado = "Vencido";
        $badge = "danger";
    } elseif ($d['fecha_vencimiento'] <= date("Y-m-d", strtotime("+30 days"))) {
        $estado = "Por vencer";
        $badge = "warning";
    } else {
        $estado = "Vigente";
        $badge = "success";
    }

    $archivo = $d['archivo']
        ? "<button class='btn btn-sm btn-primary btn-ver-pdf' 
                data-file='/uploads/documentos/vehiculos/{$d['archivo']}'>
                Ver
           </button>"
        : "—";

    $usuario = $d['usuario_nombre'] ? $d['usuario_nombre'] : "—";

    $rows .= "
        <tr>
            <td>{$d['tipo_documento']}</td>
            <td>{$d['numero']}</td>
            <td>{$d['fecha_inicio']}</td>
            <td>{$d['fecha_vencimiento']}</td>
            <td><span class='badge bg-$badge'>$estado</span></td>
            <td>$archivo</td>
            <td>$usuario</td>
            <td>{$d['observaciones']}</td>
        </tr>
    ";
}

$html = "
<div class='container-fluid'>
    <table class='table table-bordered table-striped table-sm'>
        <thead class='table-light'>
            <tr>
                <th>Tipo</th>
                <th>Número</th>
                <th>Inicio</th>
                <th>Vencimiento</th>
                <th>Estado</th>
                <th>Archivo</th>
                <th>Subido por</th>
                <th>Obs.</th>
            </tr>
        </thead>
        <tbody>
            $rows
        </tbody>
    </table>
</div>
";

echo json_encode([
    "success" => true,
    "html" => $html
]);
