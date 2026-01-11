<?php
// archivo: /modulos/vehiculos/acciones/listar_activos.php

error_reporting(0);
ini_set('display_errors', 0);

if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/funciones.php';

$conn = getConnection();

$sql = "SELECT id, placa, marca, modelo, anio, estado 
        FROM vehiculos 
        WHERE estado = 'Activo'";

$result = $conn->query($sql);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $data[] = [
            "id"     => $row['id'],
            "placa"  => $row['placa'],
            "marca"  => $row['marca'],
            "modelo" => $row['modelo'],
            "anio"   => $row['anio'],
            "estado" => $row['estado'],
            "activo" => ($row['estado'] === 'Activo' ? 1 : 0)
        ];
    }
}

echo json_encode([
    "success" => true,
    "data" => $data
]);
exit;