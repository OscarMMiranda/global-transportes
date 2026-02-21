<?php
// archivo: /modulos/asistencias/ajax/get_asistencia.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

// Validar ID
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(['ok' => false, 'error' => 'ID inválido']);
    exit;
}

// Obtener asistencia
$sql = "
    SELECT 
        ac.id,
        ac.fecha,
        ac.hora_entrada,
        ac.hora_salida,
        ac.observacion,
        ac.tipo_codigo,
        ac.empresa_id,
        ac.conductor_id,
        e.razon_social AS empresa,
        CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
        t.descripcion AS tipo_descripcion,
        t.es_feriado
    FROM asistencia_conductores ac
    INNER JOIN empresas e ON e.id = ac.empresa_id
    INNER JOIN conductores c ON c.id = ac.conductor_id
    INNER JOIN asistencia_tipos t ON t.codigo = ac.tipo_codigo
    WHERE ac.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(['ok' => false, 'error' => 'Asistencia no encontrada']);
    exit;
}

$data = $res->fetch_assoc();

// Obtener lista de tipos
$tipos = [];
$q = $conn->query("SELECT codigo, descripcion FROM asistencia_tipos ORDER BY descripcion ASC");
while ($row = $q->fetch_assoc()) {
    $tipos[] = $row;
}

$data['tipos'] = $tipos;

echo json_encode(['ok' => true, 'data' => $data]);
