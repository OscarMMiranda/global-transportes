<?php
// archivo: /modulos/asignaciones/model.php

require_once __DIR__ . '/../../includes/config.php';

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

function obtenerConductores($conn) {

    $sql = "SELECT 
                c.id,
                CONCAT(c.nombres, ' ', c.apellidos) AS nombre
            FROM conductores c
            WHERE c.activo = 1
              AND c.id NOT IN (
                    SELECT conductor_id
                    FROM asignaciones_conductor
                    WHERE estado_id = 1   -- asignación activa
              )
            ORDER BY c.nombres, c.apellidos";

    $rs = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row;
    }
    return $data;
}



function obtenerTractos($conn) {

    $sql = "SELECT 
                v.id,
                v.placa
            FROM vehiculos v
            JOIN tipo_vehiculo tv ON tv.id = v.tipo_id
            WHERE tv.categoria_id = 1   -- TRACTOS
              AND v.activo = 1
            ORDER BY v.placa";

    $rs = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row;
    }
    return $data;
}



function obtenerCarretas($conn) {

    $sql = "SELECT 
                v.id,
                v.placa
            FROM vehiculos v
            JOIN tipo_vehiculo tv ON tv.id = v.tipo_id
            WHERE tv.categoria_id = 2   -- CARRETAS / SEMIREMOLQUES
              AND v.activo = 1
            ORDER BY v.placa";

    $rs = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row;
    }
    return $data;
}


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

function obtenerConductoresDisponibles($conn) {

    $sql = "SELECT 
                c.id,
                CONCAT(c.nombres, ' ', c.apellidos) AS nombre
            FROM conductores c
            WHERE c.activo = 1
              AND c.id NOT IN (
                    SELECT conductor_id
                    FROM asignaciones_conductor
                    WHERE estado_id = 1
              )
            ORDER BY c.nombres, c.apellidos";

    $rs = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row;
    }
    return $data;
}

function obtenerConductoresTodos($conn) {

    $sql = "SELECT 
                id,
                CONCAT(nombres, ' ', apellidos) AS nombre
            FROM conductores
            WHERE activo = 1
            ORDER BY nombres, apellidos";

    $rs = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) {
        $data[] = $row;
    }
    return $data;
}

function obtenerTractosTodos($conn) {

    $sql = "SELECT 
                v.id,
                v.placa
            FROM vehiculos v
            JOIN tipo_vehiculo tv ON tv.id = v.tipo_id
            WHERE tv.categoria_id = 1   -- TRACTOS
              AND v.activo = 1
            ORDER BY v.placa";

    $rs = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) $data[] = $row;
    return $data;
}

function obtenerTractosDisponibles($conn) {

    $sql = "SELECT 
                v.id,
                v.placa
            FROM vehiculos v
            JOIN tipo_vehiculo tv ON tv.id = v.tipo_id
            WHERE tv.categoria_id = 1
              AND v.activo = 1
              AND v.id NOT IN (
                    SELECT vehiculo_tracto_id
                    FROM asignaciones_conductor
                    WHERE estado_id = 1
              )
            ORDER BY v.placa";

    $rs = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) $data[] = $row;
    return $data;
}

function obtenerCarretasTodos($conn) {

    $sql = "SELECT 
                v.id,
                v.placa
            FROM vehiculos v
            JOIN tipo_vehiculo tv ON tv.id = v.tipo_id
            WHERE tv.categoria_id = 2   -- CARRETAS / SEMIREMOLQUES
              AND v.activo = 1
            ORDER BY v.placa";

    $rs = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) $data[] = $row;
    return $data;
}

function obtenerCarretasDisponibles($conn) {

    $sql = "SELECT 
                v.id,
                v.placa
            FROM vehiculos v
            JOIN tipo_vehiculo tv ON tv.id = v.tipo_id
            WHERE tv.categoria_id = 2
              AND v.activo = 1
              AND v.id NOT IN (
                    SELECT vehiculo_remolque_id
                    FROM asignaciones_conductor
                    WHERE estado_id = 1
              )
            ORDER BY v.placa";

    $rs = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($rs)) $data[] = $row;
    return $data;
}

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
