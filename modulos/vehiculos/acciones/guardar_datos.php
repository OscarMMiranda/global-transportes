<?php
// archivo: /modulos/vehiculos/acciones/guardar_datos.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

// ------------------------------------------------------------
// VALIDAR ID
// ------------------------------------------------------------
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode([
        "ok" => false,
        "msg" => "ID inválido"
    ]);
    exit;
}

$id = intval($_POST['id']);

// ------------------------------------------------------------
// VALIDAR CAMPOS OBLIGATORIOS
// ------------------------------------------------------------
$camposObligatorios = ['placa', 'marca_id', 'modelo', 'anio', 'configuracion_id', 'empresa_id'];

foreach ($camposObligatorios as $campo) {
    if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
        echo json_encode([
            "ok" => false,
            "msg" => "El campo '$campo' es obligatorio."
        ]);
        exit;
    }
}

// Sanitizar
$placa = strtoupper(trim($_POST['placa']));
$marca_id = intval($_POST['marca_id']);
$modelo = trim($_POST['modelo']);
$anio = intval($_POST['anio']);
$tipo_id = isset($_POST['tipo_id']) ? intval($_POST['tipo_id']) : "NULL";
$configuracion_id = intval($_POST['configuracion_id']);
$empresa_id = intval($_POST['empresa_id']);

// ------------------------------------------------------------
// VALIDAR QUE LA PLACA NO SE REPITA
// ------------------------------------------------------------
$sql = "
    SELECT id 
    FROM vehiculos 
    WHERE placa = '$placa' 
      AND id != $id 
    LIMIT 1
";

$q = $conn->query($sql);

if ($q && $q->num_rows > 0) {
    echo json_encode([
        "ok" => false,
        "msg" => "La placa '$placa' ya está registrada en otro vehículo."
    ]);
    exit;
}

// ------------------------------------------------------------
// ACTUALIZAR VEHÍCULO
// ------------------------------------------------------------
$sql = "
    UPDATE vehiculos SET
        placa = '$placa',
        marca_id = $marca_id,
        modelo = '$modelo',
        anio = $anio,
        tipo_id = $tipo_id,
        configuracion_id = $configuracion_id,
        empresa_id = $empresa_id
    WHERE id = $id
    LIMIT 1
";

if (!$conn->query($sql)) {
    echo json_encode([
        "ok" => false,
        "msg" => "Error al actualizar: " . $conn->error
    ]);
    exit;
}

// ------------------------------------------------------------
// (OPCIONAL) REGISTRAR EN AUDITORÍA
// ------------------------------------------------------------
// Aquí luego agregaremos:
// registrarAuditoriaVehiculo($id, $_SESSION['usuario'], $cambiosDetectados);

// ------------------------------------------------------------
// RESPUESTA OK
// ------------------------------------------------------------
echo json_encode([
    "ok" => true,
    "msg" => "Datos técnicos actualizados correctamente."
]);
exit;
