<?php
// archivo: /modulos/vehiculos/acciones/listar.php

error_reporting(0);
ini_set('display_errors', 0);

if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/funciones.php';

$conn = getConnection();

// ---------------------------------------------------------
// 1. Leer parámetro estado
// ---------------------------------------------------------
$estado = isset($_GET['estado']) ? strtolower(trim($_GET['estado'])) : 'activo';

if ($estado === 'activo') {
    $where = "WHERE v.activo = 1";
} elseif ($estado === 'inactivo') {
    $where = "WHERE v.activo = 0";
} else {
    $where = ""; // todos
}

// ---------------------------------------------------------
// 2. Consulta SQL con JOIN a marcas
// ---------------------------------------------------------
$sql = "
    SELECT 
        v.id,
        v.placa,
        v.marca_id,
        m.nombre AS marca_nombre,
        v.modelo,
        v.anio,
        v.activo
    FROM vehiculos v
    LEFT JOIN marca_vehiculo m ON m.id = v.marca_id
    $where
";

$result = $conn->query($sql);

$data = [];

// ---------------------------------------------------------
// 3. Construcción del array asociativo
// ---------------------------------------------------------
if ($result && $result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $data[] = [
            "id"     => $row['id'],
            "placa"  => $row['placa'],
            "marca"  => $row['marca_nombre'], // ← AHORA SÍ EL NOMBRE
            "modelo" => $row['modelo'],
            "anio"   => $row['anio'],
            "estado" => ($row['activo'] == 1 ? "Activo" : "Inactivo"),
            "activo" => intval($row['activo'])
        ];
    }
}

// ---------------------------------------------------------
// 4. Respuesta JSON
// ---------------------------------------------------------
echo json_encode([
    "success" => true,
    "data"    => $data
]);
exit;