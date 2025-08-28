<?php
// modelo.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

// 🔍 Estados
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

// 🚚 Asignaciones activas
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
        JOIN conductores c ON ac.conductor_id = c.id
        JOIN estado_asignacion es ON ac.estado_id = es.id
        WHERE ac.estado_id = ?
        ORDER BY ac.fecha_inicio DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $estadoId);
    $stmt->execute();
    return $stmt->get_result();
}

// 📜 Historial de asignaciones
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
        JOIN conductores c ON ac.conductor_id = c.id
        JOIN estado_asignacion es ON ac.estado_id = es.id
        WHERE ac.estado_id = ?
        ORDER BY ac.fecha_fin DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $estadoId);
    $stmt->execute();
    return $stmt->get_result();
}

// ✅ Obtener una asignación por ID (para edición)
function getAsignacionPorId($conn, $id) {
    $sql = "
        SELECT ac.id,
               ac.conductor_id,
               ac.vehiculo_tracto_id,
               ac.vehiculo_remolque_id,
               ac.estado_id,
               ac.fecha_inicio,
               ac.fecha_fin,
               CONCAT(c.nombres, ' ', c.apellidos) AS nombre_conductor,
               vt.placa AS tracto_placa,
               vr.placa AS remolque_placa
        FROM asignaciones_conductor ac
        JOIN conductores c ON ac.conductor_id = c.id
        JOIN vehiculos vt ON ac.vehiculo_tracto_id = vt.id
        JOIN vehiculos vr ON ac.vehiculo_remolque_id = vr.id
        WHERE ac.id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// 🚛 Vehículos por categoría (1 = tracto, 2 = remolque)
function getVehiculosPorCategoria($conn, $categoria_id) {
    $sql = "
        SELECT v.id, v.placa
        FROM vehiculos v
        JOIN tipo_vehiculo tv ON v.tipo_id = tv.id
        WHERE tv.categoria_id = ?
          AND v.estado_id = (SELECT id FROM estado_vehiculo WHERE nombre = 'activo')
        ORDER BY v.placa ASC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $categoria_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehiculos = array();
    while ($row = $result->fetch_assoc()) {
        $vehiculos[] = $row;
    }
    return $vehiculos;
}

// 👤 Lista de conductores
function getConductores($conn) {
    $sql = "SELECT id, nombres, apellidos FROM conductores ORDER BY nombres ASC";
    $result = $conn->query($sql);
    $lista = array();
    while ($row = $result->fetch_assoc()) {
        $lista[] = $row;
    }
    return $lista;
}

// 🛑 Finalizar asignación con trazabilidad
function finalizarAsignacion($conn, $asignacionId, $userId) {
    $conn->autocommit(false);

    $sql1 = "
        UPDATE asignaciones_conductor
           SET estado_id     = 2,
               fecha_fin     = NOW(),
               updated_at    = NOW(),
               fecha_borrado = NOW(),
               borrado_por   = ?
         WHERE id = ?
    ";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param('ii', $userId, $asignacionId);
    if (! $stmt1->execute()) {
        $conn->rollback();
        return false;
    }
    $stmt1->close();

    $sql2 = "
        INSERT INTO asignaciones_historial
            (asignacion_id, usuario_id, accion, fecha)
        VALUES (?, ?, 'Finalizado', NOW())
    ";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('ii', $asignacionId, $userId);
    if (! $stmt2->execute()) {
        $conn->rollback();
        return false;
    }
    $stmt2->close();

    $conn->commit();
    return true;
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
