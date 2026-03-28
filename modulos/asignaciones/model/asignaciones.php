<?php
// archivo: /modulos/asignaciones/model/asignaciones.php


require_once __DIR__ . '/../../../includes/config.php';

/**
 * LISTAR TODAS LAS ASIGNACIONES
 */
function obtenerAsignaciones($conn) {

    $sql = "SELECT 
                ac.id,
                CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
                vt.placa AS tracto,
                vr.placa AS carreta,
                ac.fecha_inicio AS inicio,
                ac.fecha_fin AS fin,
                ea.nombre AS estado
            FROM asignaciones_conductor ac
            JOIN conductores c 
                ON ac.conductor_id = c.id
            JOIN vehiculos vt 
                ON ac.vehiculo_tracto_id = vt.id
            JOIN vehiculos vr 
                ON ac.vehiculo_remolque_id = vr.id
            LEFT JOIN estado_asignacion ea 
                ON ac.estado_id = ea.id
            ORDER BY ac.fecha_inicio DESC";

    $rs = mysqli_query($conn, $sql);

    if (!$rs) {
        return ['error' => mysqli_error($conn)];
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row;
    }
    return $data;
}



/**
 * OBTENER UNA ASIGNACIÓN POR ID
 */
function obtenerAsignacionPorId($conn, $id) {

    $sql = "SELECT 
                ac.id,
                CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
                vt.placa AS tracto,
                vr.placa AS carreta,
                ac.fecha_inicio AS inicio,
                ac.fecha_fin AS fin,
                ea.nombre AS estado
            FROM asignaciones_conductor ac
            JOIN conductores c ON ac.conductor_id = c.id
            JOIN vehiculos vt ON ac.vehiculo_tracto_id = vt.id
            JOIN vehiculos vr ON ac.vehiculo_remolque_id = vr.id
            LEFT JOIN estado_asignacion ea ON ac.estado_id = ea.id
            WHERE ac.id = $id";

    $rs = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($rs);
}



/**
 * GUARDAR NUEVA ASIGNACIÓN
 */
function guardarAsignacion($conn, $data) {

    $sql = "INSERT INTO asignaciones_conductor 
            (conductor_id, vehiculo_tracto_id, vehiculo_remolque_id, fecha_inicio, estado_id)
            VALUES (?, ?, ?, ?, 1)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "iiis",
        $data['conductor_id'],
        $data['tracto_id'],
        $data['carreta_id'],
        $data['inicio']
    );

    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}



/**
 * FINALIZAR ASIGNACIÓN
 */
function finalizarAsignacion($conn, $id, $fin) {

    $sql = "UPDATE asignaciones_conductor 
            SET fecha_fin = ?, estado_id = 2
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $fin, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}



/**
 * EDITAR ASIGNACIÓN
 */
function editarAsignacion($conn, $data) {

    $sql = "UPDATE asignaciones_conductor
            SET conductor_id = ?, 
                vehiculo_tracto_id = ?, 
                vehiculo_remolque_id = ?, 
                fecha_inicio = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "iiisi",
        $data['conductor_id'],
        $data['tracto_id'],
        $data['carreta_id'],
        $data['inicio'],
        $data['id']
    );

    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}



/**
 * REASIGNAR (solo cambia conductor/vehículos)
 */
function reasignarAsignacion($conn, $data) {

    $sql = "UPDATE asignaciones_conductor
            SET conductor_id = ?, 
                vehiculo_tracto_id = ?, 
                vehiculo_remolque_id = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "iiii",
        $data['conductor_id'],
        $data['tracto_id'],
        $data['carreta_id'],
        $data['id']
    );

    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}
