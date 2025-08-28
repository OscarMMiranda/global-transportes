<?php
// modelo.php — Funciones del módulo Vehículos

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

// 🔍 Obtener todos los vehículos según estado (activo/inactivo)
function obtenerVehiculos($conn, $activos = true) {
    $estado = $activos ? 'activo' : 'inactivo';

    $sql = "
        SELECT 
            v.id, v.placa, v.modelo, v.anio, v.observaciones,
            m.nombre AS marca,
            t.nombre AS tipo,
            e.razon_social AS empresa,
            ev.nombre AS estado_operativo
        FROM vehiculos v
        JOIN marca_vehiculo   m ON v.marca_id   = m.id
        JOIN tipo_vehiculo    t ON v.tipo_id    = t.id
        JOIN empresa          e ON v.empresa_id = e.id
        JOIN estado_vehiculo  ev ON v.estado_id = ev.id
        WHERE ev.nombre = ?
        ORDER BY v.placa ASC
    ";

    $stmt = $conn->prepare($sql);
    if (! $stmt) {
        error_log("[vehiculos] ERROR prepare obtenerVehiculos: " . $conn->error);
        return false;
    }

    if (! $stmt->bind_param('s', $estado)) {
        error_log("[vehiculos] ERROR bind_param obtenerVehiculos: " . $stmt->error);
        return false;
    }

    if (! $stmt->execute()) {
        error_log("[vehiculos] ERROR execute obtenerVehiculos: " . $stmt->error);
        return false;
    }

    $result = $stmt->get_result();
    if (! $result) {
        error_log("[vehiculos] ERROR get_result obtenerVehiculos: " . $stmt->error);
        return false;
    }

    return $result;
}

// 🔍 Obtener un vehículo por ID
function getVehiculoPorId($conn, $id) {
    $sql = "
        SELECT v.id, v.placa, v.modelo, v.anio, v.tipo_id, v.marca_id, v.empresa_id, v.observaciones,
               tv.nombre AS tipo,
               e.razon_social AS empresa
        FROM vehiculos v
        JOIN tipo_vehiculo tv ON v.tipo_id = tv.id
        JOIN empresa e ON v.empresa_id = e.id
        WHERE v.id = ?
    ";

    $stmt = $conn->prepare($sql);
    if (! $stmt) {
        error_log("[vehiculos] ERROR prepare getVehiculoPorId: " . $conn->error);
        return false;
    }

    if (! $stmt->bind_param('i', $id)) {
        error_log("[vehiculos] ERROR bind_param getVehiculoPorId: " . $stmt->error);
        return false;
    }

    if (! $stmt->execute()) {
        error_log("[vehiculos] ERROR execute getVehiculoPorId: " . $stmt->error);
        return false;
    }

    $stmt->store_result();
    if ($stmt->num_rows === 0) {
        return false;
    }

    $stmt->bind_result($id, $placa, $modelo, $anio, $tipo_id, $marca_id, $empresa_id, $observaciones, $tipo_nombre, $empresa_nombre);
    $stmt->fetch();

    return [
        'id'          	=> 	$id,
        'placa'       	=> 	$placa,
        'modelo'      	=> 	$modelo,
        'anio'        	=> 	$anio,
        'tipo_id'     	=> 	$tipo_id,
        'marca_id'    	=> 	$marca_id,
        'empresa_id'  	=> 	$empresa_id,
        'observaciones' => 	$observaciones,
        'tipo'        	=> 	$tipo_nombre,
        'empresa'     	=> 	$empresa_nombre
    ];
}

// 🛠️ Eliminar (soft-delete) un vehículo
function eliminarVehiculo($conn, $id, $usuarioId, $ipOrigen) {
    $sql = "
        UPDATE vehiculos
           SET estado_id     = (SELECT id FROM estado_vehiculo WHERE nombre = 'inactivo'),
               fecha_borrado = NOW(),
               borrado_por   = ?,
               ip_origen     = ?,
               updated_at    = NOW()
         WHERE id = ?
    ";

    $stmt = $conn->prepare($sql);
    if (! $stmt) {
        error_log("[vehiculos] ERROR prepare eliminarVehiculo: " . $conn->error);
        return false;
    }

    if (! $stmt->bind_param('isi', $usuarioId, $ipOrigen, $id)) {
        error_log("[vehiculos] ERROR bind_param eliminarVehiculo: " . $stmt->error);
        return false;
    }

    if (! $stmt->execute()) {
        error_log("[vehiculos] ERROR execute eliminarVehiculo: " . $stmt->error);
        return false;
    }

    return $stmt->affected_rows > 0;
}

// ♻️ Restaurar un vehículo (solo si estaba borrado)
function restaurarVehiculo($conn, $id, $usuarioId, $ipOrigen) {
    $sql = "
        UPDATE vehiculos
           SET estado_id        = (SELECT id FROM estado_vehiculo WHERE nombre = 'activo'),
               fecha_restaurado = NOW(),
               restaurado_por   = ?,
               ip_origen        = ?,
               updated_at       = NOW(),
               fecha_borrado    = NULL,
               borrado_por      = NULL
         WHERE id = ? AND fecha_borrado IS NOT NULL
    ";

    $stmt = $conn->prepare($sql);
    if (! $stmt) {
        error_log("[vehiculos] ERROR prepare restaurarVehiculo: " . $conn->error);
        return false;
    }

    if (! $stmt->bind_param('isi', $usuarioId, $ipOrigen, $id)) {
        error_log("[vehiculos] ERROR bind_param restaurarVehiculo: " . $stmt->error);
        return false;
    }

    if (! $stmt->execute()) {
        error_log("[vehiculos] ERROR execute restaurarVehiculo: " . $stmt->error);
        return false;
    }

    return $stmt->affected_rows > 0;
}

// 🧾 Registrar trazabilidad en historial
function registrarVehiculoHistorial($conn, $vehiculoId, $usuarioId, $accion) {
    $sql = "
        INSERT INTO vehiculo_historial
            (vehiculo_id, estado_anterior, estado_nuevo, motivo, usuario_id, ip_origen, user_agent, fecha)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ";

    $estadoAnterior = ($accion === 'restaurado') ? 'inactivo' : 'activo';
    $estadoNuevo    = ($accion === 'restaurado') ? 'activo' : 'inactivo';
    $motivo         = ucfirst($accion) . ' desde módulo Vehículos';
    $ipOrigen       = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
    $userAgent      = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'desconocido';

    $stmt = $conn->prepare($sql);
    if (! $stmt) {
        error_log("[vehiculo_historial] ERROR prepare: " . $conn->error);
        return false;
    }

    if (! $stmt->bind_param('ssssiss', $vehiculoId, $estadoAnterior, $estadoNuevo, $motivo, $usuarioId, $ipOrigen, $userAgent)) {
        error_log("[vehiculo_historial] ERROR bind_param: " . $stmt->error);
        return false;
    }

    if (! $stmt->execute()) {
        error_log("[vehiculo_historial] ERROR execute: " . $stmt->error);
        return false;
    }

    return true;
}

/**
 * Actualiza un vehículo en la base de datos
 * 
 * @param mysqli $conn
 * @param int    $id
 * @param string $placa
 * @param string $modelo
 * @param int    $anio
 * @param int    $tipo_id
 * @param int    $marca_id
 * @param int    $empresa_id
 * @param string $observaciones
 * @param int    $usuarioId
 * @param string $ipOrigen
 * @return bool
 */
function actualizarVehiculo(
    $conn,
    $id,
    $placa,
    $modelo,
    $anio,
    $tipo_id,
    $marca_id,
    $empresa_id,
    $observaciones,
    $usuarioId,
    $ipOrigen
) {
    $sql = "
        UPDATE vehiculos
           SET placa         = ?,
               modelo        = ?,
               anio          = ?,
               tipo_id       = ?,
               marca_id      = ?,
               empresa_id    = ?,
               observaciones = ?,
               updated_at    = NOW(),
               modificado_por= ?,
               ip_origen     = ?
         WHERE id = ?
    ";

    $stmt = $conn->prepare($sql);
    if (! $stmt) {
        error_log("[vehiculos] ERROR prepare actualizarVehiculo: " . $conn->error);
        return false;
    }

    // Tipos: s = string, i = integer
    if (! $stmt->bind_param(
        'ssiiissisi',
        $placa,
        $modelo,
        $anio,
        $tipo_id,
        $marca_id,
        $empresa_id,
        $observaciones,
        $usuarioId,
        $ipOrigen,
        $id
    )) {
        error_log("[vehiculos] ERROR bind_param actualizarVehiculo: " . $stmt->error);
        return false;
    }

    if (! $stmt->execute()) {
        error_log("[vehiculos] ERROR execute actualizarVehiculo: " . $stmt->error);
        return false;
    }

    return $stmt->affected_rows > 0;
}