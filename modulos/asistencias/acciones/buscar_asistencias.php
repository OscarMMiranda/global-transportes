<?php
// archivo: /modulos/asistencias/acciones/buscar_asistencias.php

header('Content-Type: application/json');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$conductor = isset($_POST['conductor']) ? intval($_POST['conductor']) : 0;
$periodo   = isset($_POST['periodo']) ? $_POST['periodo'] : '';
$desde     = isset($_POST['desde']) ? $_POST['desde'] : '';
$hasta     = isset($_POST['hasta']) ? $_POST['hasta'] : '';
$tipo      = isset($_POST['tipo']) ? $_POST['tipo'] : '';

if ($conductor <= 0) {
    echo json_encode(['ok' => false, 'error' => 'Debe seleccionar un conductor']);
    exit;
}

$hoy = date('Y-m-d');

switch ($periodo) {
    case 'hoy':
        $f_desde = $hoy;
        $f_hasta = $hoy;
        break;

    case 'ayer':
        $f_desde = date('Y-m-d', strtotime('-1 day'));
        $f_hasta = $f_desde;
        break;

    case 'semana':
        $f_desde = date('Y-m-d', strtotime('monday this week'));
        $f_hasta = date('Y-m-d', strtotime('sunday this week'));
        break;

    case 'mes':
        $f_desde = date('Y-m-01');
        $f_hasta = date('Y-m-t');
        break;

    case 'rango':
        if ($desde === '' || $hasta === '') {
            echo json_encode(['ok' => false, 'error' => 'Debe seleccionar un rango válido']);
            exit;
        }
        $f_desde = $desde;
        $f_hasta = $hasta;
        break;

    default:
        echo json_encode(['ok' => false, 'error' => 'Periodo inválido']);
        exit;
}

// ------------------------------------------------------------
// CONSULTA SIN TIPO
// ------------------------------------------------------------
if ($tipo === '') {

    $sql = "
        SELECT 
            ac.id,
            ac.fecha,
            ac.hora_entrada,
            ac.hora_salida,
            ac.observacion,
            t.descripcion
        FROM asistencia_conductores ac
        INNER JOIN asistencia_tipos t ON t.id = ac.tipo_id
        WHERE ac.conductor_id = ?
          AND ac.fecha BETWEEN ? AND ?
        ORDER BY ac.fecha ASC
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $conductor, $f_desde, $f_hasta);

} else {

    // ------------------------------------------------------------
    // CONSULTA CON TIPO
    // ------------------------------------------------------------
    $sql = "
        SELECT 
            ac.id,
            ac.fecha,
            ac.hora_entrada,
            ac.hora_salida,
            ac.observacion,
            t.descripcion
        FROM asistencia_conductores ac
        INNER JOIN asistencia_tipos t ON t.id = ac.tipo_id
        WHERE ac.conductor_id = ?
          AND ac.fecha BETWEEN ? AND ?
          AND ac.tipo_id = ?
        ORDER BY ac.fecha ASC
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issi", $conductor, $f_desde, $f_hasta, $tipo);
}

mysqli_stmt_execute($stmt);

// bind_result compatible con PHP 5.6 sin mysqlnd
mysqli_stmt_bind_result(
    $stmt,
    $id,
    $fecha,
    $entrada,
    $salida,
    $obs,
    $tipo_desc
);

$data = [];

while (mysqli_stmt_fetch($stmt)) {
    $data[] = [
        'id' => $id,
        'fecha' => $fecha,
        'hora_entrada' => $entrada,
        'hora_salida' => $salida,
        'observacion' => $obs,
        'tipo' => $tipo_desc
    ];
}

mysqli_stmt_close($stmt);

echo json_encode(['ok' => true, 'data' => $data]);
