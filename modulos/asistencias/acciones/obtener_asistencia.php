<?php
// archivo: /modulos/asistencias/acciones/obtener_asistencia.php

header('Content-Type: application/json');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

// CORE
require_once __DIR__ . '/../core/conductores.func.php';
require_once __DIR__ . '/../core/empresas.func.php';
require_once __DIR__ . '/../core/asistencia.func.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(['ok' => false, 'error' => 'ID inválido']);
    exit;
}

$sql = "
    SELECT 
        ac.id,
        ac.conductor_id,
        ac.tipo_id,
        t.codigo AS codigo_tipo,
        ac.hora_entrada,
        ac.hora_salida,
        ac.observacion,
        ac.fecha,
        ac.es_feriado,
        c.empresa_id,
        CONCAT(c.nombres, ' ', c.apellidos) AS conductor
    FROM asistencia_conductores ac
    INNER JOIN conductores c ON c.id = ac.conductor_id
    INNER JOIN asistencia_tipos t ON t.id = ac.tipo_id
    WHERE ac.id = ?
    LIMIT 1
";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    echo json_encode(['ok' => false, 'error' => mysqli_error($conn)]);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result(
    $stmt,
    $rid,
    $rconductor_id,
    $rtipo_id,
    $rcodigo_tipo,
    $rentrada,
    $rsalida,
    $robs,
    $rfecha,
    $res_feriado,
    $rempresa_id,
    $rconductor
);

if (!mysqli_stmt_fetch($stmt)) {
    echo json_encode(['ok' => false, 'error' => 'Registro no encontrado']);
    exit;
}

mysqli_stmt_close($stmt);

// Obtener nombre de empresa desde la tabla correcta
$empresa = obtener_empresa_por_id($conn, $rempresa_id);
$empresa_nombre = $empresa ? $empresa['razon_social'] : '';

echo json_encode([
    'ok' => true,
    'data' => [
        'id'              => $rid,
        'conductor_id'    => $rconductor_id,
        'empresa_id'      => $rempresa_id,
        'empresa_nombre'  => $empresa_nombre,
        'tipo_id'         => $rtipo_id,
        'codigo_tipo'     => $rcodigo_tipo,
        'hora_entrada'    => $rentrada,
        'hora_salida'     => $rsalida,
        'observacion'     => $robs,
        'fecha'           => $rfecha,
        'es_feriado'      => $res_feriado,
        'conductor'       => $rconductor
    ]
]);
