<?php
// archivo: /modulos/asignaciones/api/guardar.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once '../model/asignaciones.php';
$conn = getConnection();

// Datos recibidos
$conductor_id = intval($_POST['conductor_id']);
$tracto_id    = intval($_POST['tracto_id']);
$carreta_id   = intval($_POST['carreta_id']);
$inicio       = $_POST['inicio'];

$data = array(
    'conductor_id' => $conductor_id,
    'tracto_id'    => $tracto_id,
    'carreta_id'   => $carreta_id,
    'inicio'       => $inicio
);

// 1. Guardar asignación
$ok = guardarAsignacion($conn, $data);

// 2. Obtener ID recién insertado
$id = mysqli_insert_id($conn);

// 3. Registrar historial si se guardó correctamente
if ($ok && $id > 0) {

    // Construir motivo profesional
    $motivo = "Conductor: 0 → ".$conductor_id.
              " | Tracto: 0 → ".$tracto_id.
              " | Carreta: 0 → ".$carreta_id.
              " | Inicio: — → ".$inicio;

    $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;
    $rol        = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'Desconocido';
    $ip         = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';

    registrarHistorial($conn, array(
        'asignacion_id'   => $id,
        'usuario_id'      => $usuario_id,
        'accion'          => 'Creado',
        'ip_origen'       => $ip,
        'estado_anterior' => '—',
        'estado_nuevo'    => 'En curso',
        'motivo'          => $motivo,
        'rol_usuario'     => $rol
    ));
}

// 4. Respuesta JSON
echo json_encode(array(
    'ok' => $ok,
    'id' => $id
));
