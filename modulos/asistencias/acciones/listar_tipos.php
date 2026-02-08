<?php
// archivo: /modulos/asistencias/acciones/listar_tipos.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$sql = "SELECT id, descripcion FROM asistencia_tipos ORDER BY descripcion ASC";
$res = mysqli_query($conn, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($res)) {
    $data[] = $row;
}

echo json_encode([
    'ok'   => true,
    'data' => $data
]);
