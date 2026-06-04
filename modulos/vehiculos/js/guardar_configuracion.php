<?php
// archivo: /modulos/vehiculos/acciones/guardar_configuracion.php

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
// SANITIZAR CAMPOS
// ------------------------------------------------------------
$estado_id        = isset($_POST['estado_id']) ? intval($_POST['estado_id']) : null;
$kilometraje      = isset($_POST['kilometraje']) ? intval($_POST['kilometraje']) : 0;
$horas_motor      = isset($_POST['horas_motor']) ? intval($_POST['horas_motor']) : 0;
$prox_mantenimiento = isset($_POST['prox_mantenimiento']) ? intval($_POST['prox_mantenimiento']) : 0;
$centro_costo_id  = isset($_POST['centro_costo_id']) && $_POST['centro_costo_id'] !== "" 
                    ? intval($_POST['centro_costo_id']) 
                    : "NULL";

$observaciones    = isset($_POST['observaciones']) 
                    ? $conn->real_escape_string(trim($_POST['observaciones'])) 
                    : "";

// ------------------------------------------------------------
// VALIDAR CAMPOS OBLIGATORIOS
// ------------------------------------------------------------
if (!$estado_id) {
    echo json_encode([
        "ok" => false,
        "msg" => "Debe seleccionar un estado operativo."
    ]);
    exit;
}

// ------------------------------------------------------------
// ACTUALIZAR VEHÍCULO
// ------------------------------------------------------------
$sql = "
    UPDATE vehiculos SET
        estado_id = $estado_id,
        kilometraje = $kilometraje,
        horas_motor = $horas_motor,
        prox_mantenimiento = $prox_mantenimiento,
        centro_costo_id = $centro_costo_id,
        observaciones = '$observaciones'
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
// (OPCIONAL) REGISTRAR AUDITORÍA
// ------------------------------------------------------------
// registrarAuditoriaVehiculo($id, $_SESSION['usuario'], $cambiosDetectados);

// ------------------------------------------------------------
// RESPUESTA OK
// ------------------------------------------------------------
echo json_encode([
    "ok" => true,
    "msg" => "Configuración operativa actualizada correctamente."
]);
exit;
