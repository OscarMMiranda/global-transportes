<?php
// archivo: modulos/asistencias/vacaciones/ajax/ajax_movimientos.php
// ============================================================
// AJAX: LISTADO DE MOVIMIENTOS DE VACACIONES (MYSQLI + PHP 5.6)
// ============================================================


require_once __DIR__ . '/../../../../includes/config.php';

header('Content-Type: application/json');

// Parámetros recibidos
$conductor = isset($_POST['conductor']) ? $_POST['conductor'] : '';
$tipo      = isset($_POST['tipo'])      ? $_POST['tipo']      : '';
$anio      = isset($_POST['anio'])      ? $_POST['anio']      : '';

// ============================================================
// QUERY BASE
// ============================================================

$sql = "
    SELECT 
        m.id_movimiento,
        DATE_FORMAT(m.fecha, '%Y-%m-%d') AS fecha,
        m.tipo,
        m.dias,
        CONCAT(p.anio_inicio, ' - ', p.anio_fin) AS periodo,
        m.descripcion,
        m.usuario,
        m.ip
    FROM vacaciones_movimientos m
    INNER JOIN vacaciones_periodos p ON p.id_periodo = m.id_periodo
    WHERE 1 = 1
";

// ============================================================
// FILTROS
// ============================================================

if ($conductor !== '') {
    $sql .= " AND p.id_conductor = '" . $db->real_escape_string($conductor) . "' ";
}

if ($tipo !== '') {
    $sql .= " AND m.tipo = '" . $db->real_escape_string($tipo) . "' ";
}

if ($anio !== '') {
    $sql .= " AND p.anio_inicio = '" . $db->real_escape_string($anio) . "' ";
}

$sql .= " ORDER BY m.fecha DESC ";

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
