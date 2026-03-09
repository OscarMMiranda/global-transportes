<?php
// archivo: modulos/asistencias/vacaciones/ajax/ajax_anios.php

require_once __DIR__ . '/../../../../includes/config.php';
header('Content-Type: application/json');

$sql = "
    SELECT DISTINCT YEAR(periodo_inicio) AS anio
    FROM vacaciones_periodos
    ORDER BY anio DESC
";

$result = $db->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    // Debe devolver un objeto con clave "anio"
    $data[] = ["anio" => $row["anio"]];
}

echo json_encode($data);
exit;
