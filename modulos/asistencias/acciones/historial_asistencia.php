<?php
	// archivo: /modulos/asistencias/acciones/historial_asistencia.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

$sql = "
    SELECT 
        h.fecha_hora,
        h.accion,
        h.usuario,
        h.detalle
    FROM asistencia_historial h
    WHERE h.asistencia_id = ?
    ORDER BY h.fecha_hora DESC
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
    $rfecha,
    $raccion,
    $rusuario,
    $rdetalle
);

$historial = [];

while (mysqli_stmt_fetch($stmt)) {
    $historial[] = [
        'fecha_hora' => $rfecha,
        'accion'     => $raccion,
        'usuario'    => $rusuario,
        'detalle'    => $rdetalle
    ];
}

echo json_encode(['ok' => true, 'data' => $historial]);
