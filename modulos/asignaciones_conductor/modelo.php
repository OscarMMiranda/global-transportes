<?php
// modelo.php

// Obtiene la conexión mysqli de includes/config.php
    
    require_once __DIR__ . '/../../includes/config.php';

//  Obtener la conexión
    $conn = getConnection();


/**
 * Retorna el ID de un estado según su nombre.
 */
function getEstadoId($conn, $nombre) {
    $sql  = "SELECT id FROM estado_asignacion WHERE nombre = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $nombre);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) {
        die("Estado '$nombre' no existe en estado_asignacion.");
    }
    return $res->fetch_assoc()['id'];
}

/**
 * Trae las asignaciones activas.
 */
function getAsignacionesActivas($conn, $estadoId) {
    $sql = "
        SELECT ac.id,
               vt.placa AS tracto_placa,
               vr.placa AS remolque_placa,
               CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
               ac.fecha_inicio,
               es.nombre AS estado
        FROM asignaciones_conductor ac
        JOIN vehiculos vt ON ac.vehiculo_tracto_id = vt.id
        JOIN vehiculos vr ON ac.vehiculo_remolque_id = vr.id
        JOIN conductores c           ON ac.conductor_id = c.id
        JOIN estado_asignacion es    ON ac.estado_id = es.id
        WHERE ac.estado_id = ?
        ORDER BY ac.fecha_inicio DESC
    ";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Error en prepare (getAsignacionesActivas): " . $conn->error);
        return false;
    }
    $stmt->bind_param('i', $estadoId);
    $stmt->execute();
    return $stmt->get_result();
}

/**
 * Trae el historial de asignaciones finalizadas.
 */
function getHistorialAsignaciones($conn, $estadoId) {
    $sql = "
        SELECT ac.id,
               vt.placa AS tracto_placa,
               vr.placa AS remolque_placa,
               CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
               ac.fecha_inicio,
               COALESCE(ac.fecha_fin, 'En uso') AS fecha_fin,
               es.nombre AS estado
        FROM asignaciones_conductor ac
        JOIN vehiculos vt ON ac.vehiculo_tracto_id = vt.id
        JOIN vehiculos vr ON ac.vehiculo_remolque_id = vr.id
        JOIN conductores c           ON ac.conductor_id = c.id
        JOIN estado_asignacion es    ON ac.estado_id = es.id
        WHERE ac.estado_id = ?
        ORDER BY ac.fecha_fin DESC
    ";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Error en prepare (getHistorialAsignaciones): " . $conn->error);
        return false;
    }
    $stmt->bind_param('i', $estadoId);
    $stmt->execute();
    return $stmt->get_result();
}


/**
 * finalizarAsignacion
 *
 * @param mysqli $conn
 * @param int    $asignacionId
 * @param int    $userId
 * @return bool
 */
function finalizarAsignacion($conn, $asignacionId, $userId)
{
    try {
        $conn->begin_transaction();

        // 1) Actualizar estado
        $sql1 = "
            UPDATE asignaciones_conductor
            SET estado_id = (
                SELECT id FROM estado_asignacion
                WHERE LOWER(nombre) = 'finalizado' LIMIT 1
            )
            WHERE id = ?
        ";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param('i', $asignacionId);
        $stmt1->execute();

        // 2) Insertar trazabilidad
        $sql2 = "
            INSERT INTO asignaciones_historial
                (asignacion_id, usuario_id, accion, fecha)
            VALUES (?, ?, 'Finalizado', NOW())
        ";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param('ii', $asignacionId, $userId);
        $stmt2->execute();

        $conn->commit();
        return true;

    } catch (Exception $e) {
        $conn->rollback();
        error_log($e->getMessage());
        return false;
    }
}

function getAsignacionPorId($conn, $id) {
    $sql = "
        SELECT ac.*,
               CONCAT(c.nombres, ' ', c.apellidos) AS conductor,
               vt.placa AS tracto_placa,
               vr.placa AS remolque_placa
        FROM asignaciones_conductor ac
        JOIN conductores c ON ac.conductor_id = c.id
        JOIN vehiculos vt ON ac.vehiculo_tracto_id = vt.id
        JOIN vehiculos vr ON ac.vehiculo_remolque_id = vr.id
        WHERE ac.id = ?
    ";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Error en prepare (getAsignacionPorId): " . $conn->error);
        return false;
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/**
 * Inserta una nueva asignación compuesta en la base de datos.
 *
 * @param mysqli $conn
 * @param int    $conductorId
 * @param int    $tractoId
 * @param int    $remolqueId
 * @param string $fechaInicio
 * @param string $fechaFin
 * @param int    $estadoId
 * @return bool
 */
function insertAsignacion($conn, $conductorId, $tractoId, $remolqueId, $fechaInicio, $fechaFin, $estadoId) {
    $sql = "
        INSERT INTO asignaciones_conductor (
            conductor_id,
            vehiculo_tracto_id,
            vehiculo_remolque_id,
            fecha_inicio,
            fecha_fin,
            estado_id,
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, NOW())
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Error en prepare (insertAsignacion): " . $conn->error);
        return false;
    }

    $stmt->bind_param('iiissi', $conductorId, $tractoId, $remolqueId, $fechaInicio, $fechaFin, $estadoId);
    return $stmt->execute();
}
