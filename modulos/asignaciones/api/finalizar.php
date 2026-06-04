<?php
// archivo: modulos/asignaciones/api/finalizar.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once '../model/asignaciones.php';
$conn = getConnection();

$id  = intval($_POST['id']);
$fin = isset($_POST['fin']) ? $_POST['fin'] : date('Y-m-d H:i:s');

// 1. Obtener estado anterior
$asignacion = obtenerAsignacionPorId($conn, $id);
$estadoAnterior = isset($asignacion['estado']) ? $asignacion['estado'] : 'Desconocido';

// 2. Finalizar asignación
$ok = finalizarAsignacion($conn, $id, $fin);

// 3. Registrar historial SOLO si finalizó correctamente
if ($ok) {

    $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;
    $rol        = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'Desconocido';
    $ip         = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';

    registrarHistorial($conn, array(
        'asignacion_id'   => $id,
        'usuario_id'      => $usuario_id,
        'accion'          => 'Finalizado',
        'ip_origen'       => $ip,
        'estado_anterior' => $estadoAnterior,
        'estado_nuevo'    => 'Finalizado',
        'motivo'          => 'Cierre de asignación',
        'rol_usuario'     => $rol
    ));
}

// 4. Enviar respuesta JSON al final
echo json_encode(array('ok' => $ok));

