<?php
    //  archivo: /modulos/asistencias/acciones/cargar_historial_dia.php

require '../../../includes/config.php';

header('Content-Type: application/json');

$conn = getConnection();

$conductor_id = intval($_POST['conductor_id']);
$fecha        = $_POST['fecha'];

$sql = "SELECT 
            ac.id,
            ac.fecha,
            ac.hora_entrada,
            ac.hora_salida,
            ac.observacion,
            at.descripcion AS tipo
        FROM asistencia_conductores ac
        INNER JOIN asistencia_tipos at ON at.id = ac.tipo_id
        WHERE ac.conductor_id = ?
        AND ac.fecha = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $conductor_id, $fecha);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    echo json_encode(['ok' => true, 'data' => $row]);
} else {
    echo json_encode(['ok' => true, 'data' => null]);
}
