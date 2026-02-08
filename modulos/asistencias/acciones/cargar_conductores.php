<?php
// archivo: /modulos/asistencias/acciones/cargar_conductores.php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

// Validar empresa
$empresa_id = isset($_POST['empresa_id']) ? intval($_POST['empresa_id']) : 0;

if ($empresa_id <= 0) {
    echo json_encode([]);
    exit;
}

// Consulta: solo conductores activos y de la empresa seleccionada
$sql = "
    SELECT id, nombres, apellidos
    FROM conductores
    WHERE empresa_id = $empresa_id
      AND activo = 1
    ORDER BY nombres ASC, apellidos ASC
";

$result = mysqli_query($conn, $sql);

$data = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'id' => $row['id'],
            'nombre_completo' => $row['nombres'] . ' ' . $row['apellidos']
        ];
    }
}

echo json_encode($data);
