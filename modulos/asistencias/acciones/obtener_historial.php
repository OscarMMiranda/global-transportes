<?php
// archivo: /modulos/asistencias/acciones/obtener_historial.php

header('Content-Type: application/json');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(['ok' => false, 'error' => 'ID inválido']);
    exit;
}

$sql = "
    SELECT 
        id,
        asistencia_id,
        accion,
        usuario,
        detalle,
        fecha_hora
    FROM asistencia_historial
    WHERE asistencia_id = ?
    ORDER BY fecha_hora DESC
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(['ok' => false, 'error' => 'Error en la consulta SQL']);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$data = [];
while ($row = mysqli_fetch_assoc($res)) {
    $data[] = $row;
}

echo json_encode(['ok' => true, 'data' => $data]);
