<?php
    // archivo: modulos/asistencias/vacaciones/ajax/ajax_solicitudes.php
// ============================================================
// AJAX: LISTADO DE SOLICITUDES DE VACACIONES (MYSQLI + PHP 5.6)
// ============================================================

require_once __DIR__ . '/../../../../includes/config.php';


header('Content-Type: application/json');

// Parámetros
$empresa   = isset($_POST['empresa'])   ? $_POST['empresa']   : '';
$conductor = isset($_POST['conductor']) ? $_POST['conductor'] : '';
$estado    = isset($_POST['estado'])    ? $_POST['estado']    : '';

// ============================================================
// QUERY BASE
// ============================================================

$sql = "
    SELECT 
        s.id_solicitud,
        DATE_FORMAT(s.fecha_solicitud, '%Y-%m-%d') AS fecha_solicitud,
        c.nombre AS conductor,
        e.nombre AS empresa,
        DATE_FORMAT(s.fecha_inicio, '%Y-%m-%d') AS fecha_inicio,
        DATE_FORMAT(s.fecha_fin, '%Y-%m-%d') AS fecha_fin,
        s.dias,
        s.estado
    FROM vacaciones_solicitudes s
    INNER JOIN conductores c ON c.id_conductor = s.id_conductor
    INNER JOIN empresas e ON e.id_empresa = s.id_empresa
    WHERE 1 = 1
";

// ============================================================
// FILTROS
// ============================================================

if ($empresa !== '') {
    $sql .= " AND s.id_empresa = '" . $db->real_escape_string($empresa) . "' ";
}

if ($conductor !== '') {
    $sql .= " AND s.id_conductor = '" . $db->real_escape_string($conductor) . "' ";
}

if ($estado !== '') {
    $sql .= " AND s.estado = '" . $db->real_escape_string($estado) . "' ";
}

$sql .= " ORDER BY s.fecha_solicitud DESC ";

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

// Convertir a array
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// ============================================================
// RESPUESTA JSON
// ============================================================

echo json_encode($data);
exit;
