<?php
// archivo: /modulos/asistencias/acciones/obtener_asistencia/obtener_asistencia.php

header('Content-Type: application/json');

require_once __DIR__ . '/../../../../includes/config.php';
$conn = getConnection();

require_once __DIR__ . '/../core/conductores.func.php';
require_once __DIR__ . '/../core/empresas.func.php';
require_once __DIR__ . '/../core/asistencia.func.php';

require_once __DIR__ . '/validar_id.php';
require_once __DIR__ . '/query_builder.php';
require_once __DIR__ . '/ejecutar_consulta.php';
require_once __DIR__ . '/obtener_empresa.php';

$id = intval($_POST['id'] ?? 0);

$valid = validar_id($id);
if (!$valid['ok']) {
    echo json_encode($valid);
    exit;
}

$sql = query_obtener_asistencia();

$res = ejecutar_consulta_asistencia($conn, $sql, $id);
if (!$res['ok']) {
    echo json_encode($res);
    exit;
}

$data = anexar_empresa($conn, $res['data']);

echo json_encode(['ok' => true, 'data' => $data]);
