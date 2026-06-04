<?php
// archivo: /modulos/asignaciones/model/asignaciones.php

require_once __DIR__ . '/../../../includes/config.php';

/**
 * LISTAR TODAS LAS ASIGNACIONES
 */
function obtenerAsignaciones($conn, $filtros = []) {

    $where = [];

    if (!empty($filtros['conductor'])) {
        $c = mysqli_real_escape_string($conn, $filtros['conductor']);
        $where[] = "CONCAT(c.nombres, ' ', c.apellidos) LIKE '%$c%'";
    }

    if (!empty($filtros['tracto'])) {
        $t = mysqli_real_escape_string($conn, $filtros['tracto']);
        $where[] = "vt.placa LIKE '%$t%'";
    }

    if (!empty($filtros['carreta'])) {
        $r = mysqli_real_escape_string($conn, $filtros['carreta']);
        $where[] = "vr.placa LIKE '%$r%'";
    }

    if (!empty($filtros['estado'])) {
        $e = mysqli_real_escape_string($conn, $filtros['estado']);
        $where[] = "ea.nombre = '$e'";
    }

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
            LEFT JOIN estado_asignacion ea ON ac.estado_id = ea.id";

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " ORDER BY ac.fecha_inicio DESC";

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
 * OBTENER UNA ASIGNACIÓN POR ID (VERSIÓN CORRECTA)
 */
function obtenerAsignacionPorId($conn, $id) {

    $sql = "SELECT 
                ac.id,
                ac.conductor_id,
                ac.vehiculo_tracto_id AS tracto_id,
                ac.vehiculo_remolque_id AS carreta_id,
                ac.estado_id,

                CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
                vt.placa AS tracto,
                vr.placa AS carreta,
                ea.nombre AS estado,

                DATE_FORMAT(ac.fecha_inicio, '%Y-%m-%dT%H:%i') AS inicio,

                DATE_FORMAT(ac.fecha_fin, '%Y-%m-%dT%H:%i') AS fin

            FROM asignaciones_conductor ac
            JOIN conductores c ON ac.conductor_id = c.id
            JOIN vehiculos vt ON ac.vehiculo_tracto_id = vt.id
            JOIN vehiculos vr ON ac.vehiculo_remolque_id = vr.id
            LEFT JOIN estado_asignacion ea ON ac.estado_id = ea.id
            WHERE ac.id = $id
            LIMIT 1";

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

function construirWhere($conn, $filtros) {
    $where = array();

    if ($filtros['conductor'] !== '') {
        $c = mysqli_real_escape_string($conn, $filtros['conductor']);
        $where[] = "CONCAT(c.nombres, ' ', c.apellidos) LIKE '%$c%'";
    }

    if ($filtros['tracto'] !== '') {
        $t = mysqli_real_escape_string($conn, $filtros['tracto']);
        $where[] = "vt.placa LIKE '%$t%'";
    }

    if ($filtros['carreta'] !== '') {
        $r = mysqli_real_escape_string($conn, $filtros['carreta']);
        $where[] = "vr.placa LIKE '%$r%'";
    }

    if ($filtros['estado'] !== '') {
        $e = mysqli_real_escape_string($conn, $filtros['estado']);
        $where[] = "ea.nombre = '$e'";
    }

    return $where;
}

function obtenerConductoresFiltrados($conn, $filtros) {

    $where = construirWhere($conn, $filtros);

    $sql = "SELECT DISTINCT CONCAT(c.nombres, ' ', c.apellidos) AS conductor
            FROM asignaciones_conductor ac
            JOIN conductores c ON ac.conductor_id = c.id
            JOIN vehiculos vt ON ac.vehiculo_tracto_id = vt.id
            JOIN vehiculos vr ON ac.vehiculo_remolque_id = vr.id
            LEFT JOIN estado_asignacion ea ON ac.estado_id = ea.id";

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $rs = mysqli_query($conn, $sql);
    $data = array();

    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row['conductor'];
    }

    return $data;
}

function obtenerTractosFiltrados($conn, $filtros) {

    $where = construirWhere($conn, $filtros);

    $sql = "SELECT DISTINCT vt.placa AS tracto
            FROM asignaciones_conductor ac
            JOIN conductores c ON ac.conductor_id = c.id
            JOIN vehiculos vt ON ac.vehiculo_tracto_id = vt.id
            JOIN vehiculos vr ON ac.vehiculo_remolque_id = vr.id
            LEFT JOIN estado_asignacion ea ON ac.estado_id = ea.id";

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $rs = mysqli_query($conn, $sql);
    $data = array();

    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row['tracto'];
    }

    return $data;
}

function obtenerCarretasFiltradas($conn, $filtros) {

    $where = construirWhere($conn, $filtros);

    $sql = "SELECT DISTINCT vr.placa AS carreta
            FROM asignaciones_conductor ac
            JOIN conductores c ON ac.conductor_id = c.id
            JOIN vehiculos vt ON ac.vehiculo_tracto_id = vt.id
            JOIN vehiculos vr ON ac.vehiculo_remolque_id = vr.id
            LEFT JOIN estado_asignacion ea ON ac.estado_id = ea.id";

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $rs = mysqli_query($conn, $sql);
    $data = array();

    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row['carreta'];
    }

    return $data;
}

function obtenerEstadosFiltrados($conn, $filtros) {

    $where = construirWhere($conn, $filtros);

    $sql = "SELECT DISTINCT ea.nombre AS estado
            FROM asignaciones_conductor ac
            JOIN conductores c ON ac.conductor_id = c.id
            JOIN vehiculos vt ON ac.vehiculo_tracto_id = vt.id
            JOIN vehiculos vr ON ac.vehiculo_remolque_id = vr.id
            LEFT JOIN estado_asignacion ea ON ac.estado_id = ea.id";

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $rs = mysqli_query($conn, $sql);
    $data = array();

    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row['estado'];
    }

    return $data;
}

function obtenerHistorial($conn, $id) {

    $sql = "SELECT 
                id,
                asignacion_id,
                usuario_id,
                accion,
                DATE_FORMAT(fecha, '%Y-%m-%d %H:%i') AS fecha,
                ip_origen,
                estado_anterior,
                estado_nuevo,
                motivo,
                rol_usuario
            FROM asignaciones_historial
            WHERE asignacion_id = $id
            ORDER BY fecha DESC";

    $rs = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row;
    }

    return $data;
}

function registrarHistorial($conn, $data) {

    $sql = "INSERT INTO asignaciones_historial 
            (asignacion_id, usuario_id, accion, fecha, ip_origen, 
             estado_anterior, estado_nuevo, motivo, rol_usuario)
            VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "iissssss",
        $data['asignacion_id'],
        $data['usuario_id'],
        $data['accion'],
        $data['ip_origen'],
        $data['estado_anterior'],
        $data['estado_nuevo'],
        $data['motivo'],
        $data['rol_usuario']
    );

    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

