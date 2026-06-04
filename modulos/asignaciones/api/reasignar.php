<?php
// archivo: modulos/asignaciones/api/reasignar.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once '../model/asignaciones.php';
$conn = getConnection();

// Datos recibidos
$id           = intval($_POST['id']);
$conductor_id = intval($_POST['conductor_id']);
$tracto_id    = intval($_POST['tracto_id']);
$carreta_id   = intval($_POST['carreta_id']);

// 1. Obtener datos anteriores
$old = obtenerAsignacionPorId($conn, $id);

// 2. Detectar cambios
$cambios = array();

if ($old['conductor_id'] != $conductor_id) {
    $cambios[] = "Conductor: ".$old['conductor_id']." → ".$conductor_id;
}

if ($old['tracto_id'] != $tracto_id) {
    $cambios[] = "Tracto: ".$old['tracto_id']." → ".$tracto_id;
}

if ($old['carreta_id'] != $carreta_id) {
    $cambios[] = "Carreta: ".$old['carreta_id']." → ".$carreta_id;
}

// 3. Si no hubo cambios, no registrar historial
if (count($cambios) == 0) {
    echo json_encode(array('ok' => true, 'msg' => 'Sin cambios'));
    exit;
}

// 4. Reasignar
$data = array(
    'id'           => $id,
    'conductor_id' => $conductor_id,
    'tracto_id'    => $tracto_id,
    'carreta_id'   => $carreta_id
);

$ok = reasignarAsignacion($conn, $data);

// 5. Registrar historial si la reasignación fue exitosa
if ($ok) {

    $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;
    $rol        = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'Desconocido';
    $ip         = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';

    registrarHistorial($conn, array(
        'asignacion_id'   => $id,
        'usuario_id'      => $usuario_id,
        'accion'          => 'Reasignado',
        'ip_origen'       => $ip,
        'estado_anterior' => $old['estado'],
        'estado_nuevo'    => $old['estado'], // no cambia estado
        'motivo'          => implode(" | ", $cambios),
        'rol_usuario'     => $rol
    ));
}

// 6. Respuesta JSON
echo json_encode(array('ok' => $ok));
