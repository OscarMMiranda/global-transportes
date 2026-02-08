<?php
// archivo: /modulos/asistencias/acciones/cargar_asistencia.php

require __DIR__ . '/../../../includes/config.php';

// Cargar funciones reales del módulo
require __DIR__ . '/../core/asistencia.func.php';
require __DIR__ . '/../core/empresas.func.php';
require __DIR__ . '/../core/conductores.func.php';
require __DIR__ . '/../core/fechas.func.php';
require __DIR__ . '/../core/matriz.func.php';
require __DIR__ . '/../core/helpers.func.php';

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = getConnection();

// ============================================================
// VALIDAR PARAMETRO
// ============================================================
if (!isset($_POST['asistencia_id'])) {
    echo json_encode([
        'ok' => false,
        'error' => 'ID de asistencia no recibido'
    ]);
    exit;
}

$asistencia_id = intval($_POST['asistencia_id']);

// ============================================================
// CONSULTAR ASISTENCIA
// ============================================================
$sql = "SELECT 
            ac.id,
            ac.conductor_id,
            ac.fecha,
            ac.tipo_id,
            ac.hora_entrada,
            ac.hora_salida,
            ac.es_feriado,
            ac.observacion,
            c.nombre_completo AS conductor_nombre,
            c.empresa_id,
            t.codigo AS codigo_tipo
        FROM asistencia_conductores ac
        INNER JOIN conductores c ON c.id = ac.conductor_id
        INNER JOIN asistencia_tipos t ON t.id = ac.tipo_id
        WHERE ac.id = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $asistencia_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'ok' => false,
        'error' => 'Asistencia no encontrada'
    ]);
    exit;
}

$data = $result->fetch_assoc();

// ============================================================
// RESPUESTA JSON
// ============================================================
echo json_encode([
    'ok' => true,
    'data' => [
        'id'               => $data['id'],
        'empresa_id'       => $data['empresa_id'],
        'conductor_id'     => $data['conductor_id'],
        'conductor_nombre' => $data['conductor_nombre'],
        'codigo_tipo'      => $data['codigo_tipo'],
        'fecha'            => $data['fecha'],
        'hora_entrada'     => $data['hora_entrada'],
        'hora_salida'      => $data['hora_salida'],
        'es_feriado'       => $data['es_feriado'],
        'observacion'      => $data['observacion']
    ]
]);
