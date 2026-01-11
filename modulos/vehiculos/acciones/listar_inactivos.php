<?php
// archivo: /modulos/vehiculos/acciones/listar_inactivos.php

// Evitar que warnings rompan el JSON
error_reporting(0);
ini_set('display_errors', 0);

if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../../../includes/config.php';
require_once INCLUDES_PATH . '/funciones.php';

$conn = getConnection();

// Consulta de vehículos inactivos
$sql = "SELECT id, placa, marca, modelo, anio, estado 
        FROM vehiculos 
        WHERE estado = 'Inactivo'";

$result = $conn->query($sql);

$data = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $acciones = '
            <button class="btn btn-sm btn-info btn-view" data-id="' . $row['id'] . '">
                <i class="fa-solid fa-eye"></i>
            </button>
        ';

        $data[] = array(
            $row['id'],
            $row['placa'],
            $row['marca'],
            $row['modelo'],
            $row['anio'],
            $row['estado'],
            $acciones
        );
    }
}

// Respuesta JSON para DataTables
echo json_encode(array("data" => $data));
exit;