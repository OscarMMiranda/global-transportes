<?php
// archivo: modulos/asistencias/vacaciones/ajax/ajax_periodos.php
require_once __DIR__ . '/../../../../includes/config.php';
header('Content-Type: application/json');

$id_periodo = isset($_POST['id_periodo']) ? intval($_POST['id_periodo']) : 0;

if ($id_periodo <= 0) {
    echo json_encode(array("error" => true, "mensaje" => "ID inválido"));
    exit;
}

// ============================================================
// DETALLE DEL PERIODO
// ============================================================

$sql_detalle = "
    SELECT 
        p.id,
        p.conductor_id,
        DATE_FORMAT(p.periodo_inicio, '%Y-%m-%d') AS periodo_inicio,
        DATE_FORMAT(p.periodo_fin, '%Y-%m-%d') AS periodo_fin,
        p.dias_ganados,
        p.dias_usados,
        p.dias_vendidos,
        p.dias_pendientes,
        p.estado
    FROM vacaciones_periodos p
    WHERE p.id = $id_periodo
";

$result_detalle = $db->query($sql_detalle);

if (!$result_detalle) {
    echo json_encode(array("error" => true, "mensaje" => "Error SQL detalle: " . $db->error));
    exit;
}

$detalle = $result_detalle->fetch_assoc();

if (!$detalle) {
    echo json_encode(array("error" => true, "mensaje" => "Periodo no encontrado"));
    exit;
}

// ============================================================
// MOVIMIENTOS DEL PERIODO
// ============================================================

$sql_mov = "
    SELECT 
        m.id_movimiento,
        DATE_FORMAT(m.fecha, '%Y-%m-%d') AS fecha,
        m.tipo,
        m.dias,
        m.descripcion,
        m.usuario,
        m.ip
    FROM vacaciones_movimientos m
    WHERE m.id_periodo = $id_periodo
    ORDER BY m.fecha DESC
";

$result_mov = $db->query($sql_mov);

$movimientos = array();
if ($result_mov) {
    while ($row = $result_mov->fetch_assoc()) {
        $movimientos[] = $row;
    }
}

// ============================================================
// RESPUESTA
// ============================================================

echo json_encode(array(
    "detalle" => $detalle,
    "movimientos" => $movimientos
));
exit;
