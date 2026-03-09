<?php
    // archivo: modulos/asistencias/vacaciones/ajax/ajax_conductores.php

require_once __DIR__ . '/../../../../includes/config.php';
header('Content-Type: application/json');

$empresa = isset($_POST['empresa']) ? intval($_POST['empresa']) : 0;

$sql = "
    SELECT 
        id,
        CONCAT(nombres, ' ', apellidos) AS nombre
    FROM conductores
    WHERE activo = 1
";

if ($empresa > 0) {
    $sql .= " AND empresa_id = " . $empresa;
}

$sql .= " ORDER BY nombres ASC";

$result = $db->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
exit;
