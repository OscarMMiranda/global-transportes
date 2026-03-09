<?php
    // archivo: modulos/asistencias/vacaciones/ajax/ajax_empresas.php

require_once __DIR__ . '/../../../../includes/config.php';
header('Content-Type: application/json');

$sql = "SELECT id, razon_social FROM empresa ORDER BY razon_social ASC";

$result = $db->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
exit;
