<?php
// archivo: /modulos/asistencias/vacaciones/ajax/ajax_saldos.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ============================================================
// AJAX: OBTENER SALDOS DE VACACIONES (MYSQLI + PHP 5.6)
// ============================================================

require_once __DIR__ . '/../../../../includes/config.php';

header('Content-Type: application/json');

// Parámetros
$empresa   = isset($_POST['empresa'])   ? $_POST['empresa']   : '';
$conductor = isset($_POST['conductor']) ? $_POST['conductor'] : '';
$anio      = isset($_POST['anio'])      ? $_POST['anio']      : '';

// ============================================================
// QUERY BASE (CORREGIDA)
// ============================================================

$sql = "
    SELECT 
        p.id,
        CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
        e.razon_social AS empresa,
        CONCAT(
            DATE_FORMAT(p.periodo_inicio, '%Y'),
            ' - ',
            DATE_FORMAT(p.periodo_fin, '%Y')
        ) AS periodo,
        p.dias_ganados,
        p.dias_usados,
        p.dias_vendidos,
        p.dias_pendientes,
        p.estado
    FROM vacaciones_periodos p
    INNER JOIN conductores c ON c.id = p.conductor_id
    INNER JOIN empresa e ON e.id = c.empresa_id
    WHERE 1 = 1
";

// Filtros
if ($empresa !== '') {
    $sql .= " AND e.id = '" . $db->real_escape_string($empresa) . "' ";
}

if ($conductor !== '') {
    $sql .= " AND p.conductor_id = '" . $db->real_escape_string($conductor) . "' ";
}

if ($anio !== '') {
    $sql .= " AND YEAR(p.periodo_inicio) = '" . $db->real_escape_string($anio) . "' ";
}

$sql .= " ORDER BY p.periodo_inicio DESC ";

// ============================================================
// EJECUCIÓN
// ============================================================

$result = $db->query($sql);

if (!$result) {
    echo json_encode(array(
        "error" => true,
        "mensaje" => "Error SQL: " . $db->error
    ));
    exit;
}

// Convertir a array asociativo
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// ============================================================
// RESPUESTA JSON
// ============================================================

echo json_encode($data);
exit;


