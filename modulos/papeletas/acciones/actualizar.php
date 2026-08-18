<?php
// archivo: /modulos/papeletas/acciones/actualizar.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// incluir log
require_once $_SERVER['DOCUMENT_ROOT'] . '/modulos/papeletas/acciones/log_registrar.php';

ini_set('display_errors', 0);

/* ============================================================
   VALIDAR ID
   ============================================================ */
$id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;

if ($id <= 0) {
    echo json_encode(["success" => false, "msg" => "ID inválido"]);
    exit;
}

/* ============================================================
   OBTENER DATOS ACTUALES PARA AUDITORÍA
   ============================================================ */
$sqlActual = "SELECT * FROM papeletas WHERE id = $id LIMIT 1";
$resActual = mysqli_query($conn, $sqlActual);

if (!$resActual || mysqli_num_rows($resActual) == 0) {
    echo json_encode(["success" => false, "msg" => "La papeleta no existe"]);
    exit;
}

$actual = mysqli_fetch_assoc($resActual);

/* ============================================================
   BLOQUEAR EDICIÓN SI LA PAPELETA ESTÁ PAGADA
   ============================================================ */
if (intval($actual['estado_id']) === 7) { // 7 = PAGADA
    echo json_encode([
        "success" => false,
        "msg" => "No se puede editar una papeleta pagada."
    ]);
    exit;
}

/* ============================================================
   VALIDAR INFRACCIÓN
   ============================================================ */
if (!isset($_POST['infraccion_id']) || $_POST['infraccion_id'] === "") {
    echo json_encode(["success" => false, "msg" => "La infracción es obligatoria"]);
    exit;
}

/* ============================================================
   CAMPOS
   ============================================================ */
$vehiculo_id        = intval($_POST['vehiculo_id']);
$entidad_emisora_id = intval($_POST['entidad_emisora_id']);
$infraccion_id      = intval($_POST['infraccion_id']);

$conductor_id = $_POST['conductor_id'];
$conductor_id = ($conductor_id === "" ? "NULL" : intval($conductor_id));

$fecha_infraccion   = mysqli_real_escape_string($conn, $_POST['fecha_infraccion']);
$fecha_notificacion = mysqli_real_escape_string($conn, $_POST['fecha_notificacion']);
$fecha_vencimiento  = mysqli_real_escape_string($conn, $_POST['fecha_vencimiento']);

$lugar              = mysqli_real_escape_string($conn, $_POST['lugar']);
$descripcion        = mysqli_real_escape_string($conn, $_POST['descripcion']);
$codigo_papeleta    = mysqli_real_escape_string($conn, $_POST['codigo_papeleta']);

/* ============================================================
   VALIDAR CÓDIGO DE PAPELETA DUPLICADO
   ============================================================ */
$sqlCheck = "
    SELECT id 
    FROM papeletas 
    WHERE codigo_papeleta = '$codigo_papeleta'
      AND id != $id
    LIMIT 1
";

$resCheck = mysqli_query($conn, $sqlCheck);

if (mysqli_num_rows($resCheck) > 0) {
    echo json_encode([
        "success" => false,
        "msg" => "El código de papeleta ya está registrado."
    ]);
    exit;
}

/* ============================================================
   UPDATE
   ============================================================ */
$sql = "
UPDATE papeletas SET
    vehiculo_id        = $vehiculo_id,
    conductor_id       = $conductor_id,
    entidad_emisora_id = $entidad_emisora_id,
    infraccion_id      = $infraccion_id,
    fecha_infraccion   = '$fecha_infraccion',
    fecha_notificacion = '$fecha_notificacion',
    fecha_vencimiento  = '$fecha_vencimiento',
    lugar              = '$lugar',
    descripcion        = '$descripcion',
    codigo_papeleta    = '$codigo_papeleta',
    updated_at         = NOW()
WHERE id = $id
";

if (!mysqli_query($conn, $sql)) {
    echo json_encode(["success" => false, "msg" => mysqli_error($conn)]);
    exit;
}

/* ============================================================
   REGISTRAR CAMBIOS EN LOG
   ============================================================ */
function registrarCambio($campo, $antes, $despues, $id) {
    if ($antes != $despues) {
        registrarLog($id, 'edicion', "$campo: '$antes' → '$despues'");
    }
}

registrarCambio('Vehículo', $actual['vehiculo_id'], $vehiculo_id, $id);
registrarCambio('Conductor', $actual['conductor_id'], $conductor_id, $id);
registrarCambio('Entidad emisora', $actual['entidad_emisora_id'], $entidad_emisora_id, $id);
registrarCambio('Infracción', $actual['infraccion_id'], $infraccion_id, $id);

registrarCambio('Fecha infracción', $actual['fecha_infraccion'], $fecha_infraccion, $id);
registrarCambio('Fecha notificación', $actual['fecha_notificacion'], $fecha_notificacion, $id);
registrarCambio('Fecha vencimiento', $actual['fecha_vencimiento'], $fecha_vencimiento, $id);

registrarCambio('Lugar', $actual['lugar'], $lugar, $id);
registrarCambio('Descripción', $actual['descripcion'], $descripcion, $id);
registrarCambio('Código papeleta', $actual['codigo_papeleta'], $codigo_papeleta, $id);

echo json_encode(["success" => true]);
exit;
?>
