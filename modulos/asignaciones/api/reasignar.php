<?php
	//	archivo: modulos/asignaciones/api/reasignar.php

require_once '../model/asignaciones.php';
$conn = getConnection();

$data = [
    'id'           => intval($_POST['id']),
    'conductor_id' => intval($_POST['conductor_id']),
    'tracto_id'    => intval($_POST['tracto_id']),
    'carreta_id'   => intval($_POST['carreta_id'])
];

echo json_encode([
    'ok' => reasignarAsignacion($conn, $data)
]);
