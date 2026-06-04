<?php
// archivo: /modulos/orden_trabajo/controllers/RegistrarViajeController.php

header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
$conn = getConnection();

// ===============================
// 🔵 VALIDAR MÉTODO
// ===============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Método no permitido"
    ));
    exit;
}

// ===============================
// 🔵 VALIDAR CAMPOS OBLIGATORIOS
// ===============================
$campos = array(
    'orden_trabajo_id',
    'vehiculo_id',
    'fecha_salida',
    'origen_id',
    'destino_id'
);

foreach ($campos as $campo) {
    if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
        echo json_encode(array(
            "estado" => "error",
            "mensaje" => "Falta el campo: " . $campo
        ));
        exit;
    }
}

// ===============================
// 🔵 SANITIZAR DATOS
// ===============================
$ordenID      = intval($_POST['orden_trabajo_id']);
$vehiculoID   = intval($_POST['vehiculo_id']);
$fechaSalida  = mysqli_real_escape_string($conn, trim($_POST['fecha_salida']));
$fechaLlegada = $fechaSalida; // por defecto
$origenID     = intval($_POST['origen_id']);
$destinoID    = intval($_POST['destino_id']);

$semanaViaje  = date('W', strtotime($fechaSalida));
$distanciaKM  = 0;
$chofer       = "No asignado";
$estado       = "Programado";
$observaciones = "";

// ===============================
// 🔵 VALIDAR RELACIÓN ORDEN - VEHÍCULO
// ===============================
$sqlRel = "
    SELECT id 
    FROM ordenes_vehiculo 
    WHERE orden_trabajo_id = ? AND vehiculo_id = ?
    LIMIT 1
";

$stmtRel = $conn->prepare($sqlRel);

if (!$stmtRel) {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Error preparando consulta de relación: " . $conn->error
    ));
    exit;
}

$stmtRel->bind_param("ii", $ordenID, $vehiculoID);
$stmtRel->execute();
$resRel = $stmtRel->get_result();

if ($resRel->num_rows === 0) {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "No existe relación entre la orden y el vehículo"
    ));
    exit;
}

$ordenVehiculoID = $resRel->fetch_assoc()['id'];

// ===============================
// 🔵 INSERTAR VIAJE
// ===============================
$sqlInsert = "
    INSERT INTO viajes_orden (
        orden_vehiculo_id, fecha_salida, fecha_llegada, semana_viaje,
        distancia_km, chofer, estado, observaciones, origen_id, destino_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($sqlInsert);

if (!$stmt) {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Error preparando inserción: " . $conn->error
    ));
    exit;
}

$stmt->bind_param(
    "ississssii",
    $ordenVehiculoID,
    $fechaSalida,
    $fechaLlegada,
    $semanaViaje,
    $distanciaKM,
    $chofer,
    $estado,
    $observaciones,
    $origenID,
    $destinoID
);

if ($stmt->execute()) {
    echo json_encode(array(
        "estado" => "ok",
        "mensaje" => "Viaje registrado correctamente",
        "semana"  => $semanaViaje
    ));
    exit;
} else {
    echo json_encode(array(
        "estado" => "error",
        "mensaje" => "Error al registrar viaje: " . $stmt->error
    ));
    exit;
}
