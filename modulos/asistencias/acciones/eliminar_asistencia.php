<?php
    // archivo: /modulos/asistencias/acciones/eliminar_asistencia.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();



$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(['ok' => false, 'error' => 'ID inválido']);
    exit;
}

$sql = "DELETE FROM asistencia_conductores WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    echo json_encode(['ok' => false, 'error' => mysqli_error($conn)]);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $id);
$ok = mysqli_stmt_execute($stmt);

if ($ok) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'error' => mysqli_error($conn)]);
}
