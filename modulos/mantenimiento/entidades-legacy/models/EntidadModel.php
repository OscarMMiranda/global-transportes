<?php
      //  archivo : /modulos/mantenimiento/entidades/models/EntidadModel.php


function obtenerEntidades($conn) {
    $sql = "SELECT e.*, d.nombre AS departamento
            FROM entidades e
            LEFT JOIN departamentos d ON e.departamento_id = d.id
            ORDER BY e.nombre ASC";
    $result = mysqli_query($conn, $sql);
    $entidades = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $entidades[] = $row;
    }

    return $entidades;
}

function obtenerEntidadPorId($conn, $id) {
    $id = intval($id);
    error_log("🔍 Buscando entidad con ID: $id");

    $sql = "SELECT e.*, d.nombre AS departamento
            FROM entidades e
            LEFT JOIN departamentos d ON e.departamento_id = d.id
            WHERE e.id = $id
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        error_log("✅ Entidad encontrada");
        return mysqli_fetch_assoc($result);
    }

    error_log("⚠️ No se encontró entidad con ID: $id");
    return null;
}

function obtenerEntidadesSeparadas($conn) {
    $todos = obtenerEntidades($conn);
    $activos = array();
    $inactivos = array();

    foreach ($todos as $e) {
        $estado = isset($e['estado']) ? strtolower(trim($e['estado'])) : '';
        if ($estado === 'activo') {
            $activos[] = $e;
        } elseif ($estado === 'inactivo') {
            $inactivos[] = $e;
        }
    }

    return array('activos' => $activos, 'inactivos' => $inactivos);
}

function insertarEntidad($conn, $data) {
    $sql = "INSERT INTO entidades (nombre, ruc, direccion, departamento_id, provincia_id, distrito_id, tipo_id, creado_por)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, 'sssiiiis',
        $data['nombre'], $data['ruc'], $data['direccion'],
        intval($data['departamento_id'] ?: 0),
        intval($data['provincia_id'] ?: 0),
        intval($data['distrito_id'] ?: 0),
        intval($data['tipo_id'] ?: 0),
        $data['creado_por']
    );

    $ok = mysqli_stmt_execute($stmt);
    $id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    if ($ok) {
        registrarLogEntidad($conn, $id, 'insertar', $data['creado_por'], '', $data['nombre'], null, $data['tipo_id']);
    }

    return $ok;
}

function editarEntidad($conn, $id, $data) {
    $sql = "SELECT nombre, tipo_id FROM entidades WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    $nombreAnterior = $row['nombre'];
    $tipoAnterior = $row['tipo_id'];

    $sql = "UPDATE entidades SET nombre = ?, ruc = ?, direccion = ?, departamento_id = ?, provincia_id = ?, distrito_id = ?, tipo_id = ?, fecha_modificacion = NOW()
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssiiiis',
        $data['nombre'], $data['ruc'], $data['direccion'],
        intval($data['departamento_id'] ?: 0),
        intval($data['provincia_id'] ?: 0),
        intval($data['distrito_id'] ?: 0),
        intval($data['tipo_id'] ?: 0),
        $id
    );
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($ok) {
        registrarLogEntidad($conn, $id, 'editar', $data['creado_por'], $nombreAnterior, $data['nombre'], $tipoAnterior, $data['tipo_id']);
    }

    return $ok;
}

function eliminarEntidad($conn, $id, $usuario) {
    $sql = "UPDATE entidades SET estado = 'inactivo' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($ok) {
        registrarLogEntidad($conn, $id, 'eliminar', $usuario, '', '', null, null);
    }

    return $ok;
}

function restaurarEntidad($conn, $id, $usuario) {
    $sql = "UPDATE entidades SET estado = 'activo' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($ok) {
        registrarLogEntidad($conn, $id, 'restaurar', $usuario, '', '', null, null);
    }

    return $ok;
}

function registrarLogEntidad($conn, $entidadId, $accion, $usuario, $nombreAnterior, $nombreNuevo, $tipoAnterior, $tipoNuevo) {
    $sql = "INSERT INTO log_entidad_local (entidad_id, accion, usuario, nombre_anterior, nombre_nuevo, tipo_anterior, tipo_nuevo, fecha)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'issssii',
        $entidadId, $accion, $usuario,
        $nombreAnterior, $nombreNuevo,
        $tipoAnterior, $tipoNuevo
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function actualizarEntidad($conn, $id, $datos) {
    if (!($conn instanceof mysqli)) return false;

    // Normalizar valores enteros
    $datos['departamento_id'] = intval($datos['departamento_id'] ?: 0);
    $datos['provincia_id']    = intval($datos['provincia_id'] ?: 0);
    $datos['distrito_id']     = intval($datos['distrito_id'] ?: 0);
    $datos['tipo_id']         = intval($datos['tipo_id'] ?: 0);

    $stmt = $conn->prepare("
        UPDATE entidades SET
            nombre = ?,
            ruc = ?,
            direccion = ?,
            departamento_id = ?,
            provincia_id = ?,
            distrito_id = ?,
            tipo_id = ?
        WHERE id = ?
    ");

    if (!$stmt) {
        error_log("❌ Error al preparar UPDATE: " . $conn->error);
        return false;
    }

    error_log("🧪 Preparando bind_param para ID $id");

    $stmt->bind_param(
        'sssiiiii',
        $datos['nombre'],
        $datos['ruc'],
        $datos['direccion'],
        $datos['departamento_id'],
        $datos['provincia_id'],
        $datos['distrito_id'],
        $datos['tipo_id'],
        $id
    );

    error_log("🧪 Parámetros bind_param listos");

    $exito = $stmt->execute();
    error_log("✅ UPDATE ejecutado. Afectó " . $stmt->affected_rows . " filas.");

    if (!$exito) {
        error_log("❌ Error al ejecutar UPDATE: " . $stmt->error);
    }

    $stmt->close();
    return $exito;
}
?>