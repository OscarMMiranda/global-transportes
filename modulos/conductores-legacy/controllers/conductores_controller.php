<?php
// archivo	:	/modulos/conductores/controllers/conductores_controller.php


/**
 * Helper para preparar statements con chequeo de errores (MySQLi)
 * @throws Exception
 */
function prep($conn, $sql) {
    if (!$conn || !($conn instanceof mysqli)) {
        throw new Exception("❌ Conexión inválida en prep()\nSQL: $sql");
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("❌ Error en prepare(): ({$conn->errno}) {$conn->error}\nSQL: $sql");
    }

    return $stmt;
}

/**
 * Lista todos los conductores (activos por defecto)
 */
function listarConductores($conn, $incluirInactivos = false) {
    $sql = "SELECT id, nombres, apellidos, dni, licencia_conducir, telefono, activo, foto 
            FROM conductores";
    if (!$incluirInactivos) {
        $sql .= " WHERE activo = 1";
    }
    $sql .= " ORDER BY apellidos, nombres";

    $stmt = prep($conn, $sql);
    if (!$stmt->execute()) {
        throw new Exception("❌ Error en execute(): {$stmt->error}");
    }

    $res = $stmt->get_result();
    $conductores = [];

    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $conductores[] = $row;
        }
    }

    $stmt->close();
    return $conductores;
}
/**
 * Obtiene un conductor por ID (compatible con PHP 5.6 sin mysqlnd)
 */
function obtenerConductorPorId($conn, $id) {
    $stmt = $conn->prepare("SELECT id, nombres, apellidos, dni, licencia_conducir, telefono, correo, direccion, activo, foto FROM conductores WHERE id = ?");
    if (!$stmt) {
        error_log("❌ Error en prepare: " . $conn->error);
        return null;
    }

    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        error_log("❌ Error en execute: " . $stmt->error);
        return null;
    }

    // Variables locales para evitar colisión con parámetro $id
    $id_conductor = $nombres = $apellidos = $dni = $licencia = $telefono = $correo = $direccion = $foto = '';
    $activo = 0;

    $stmt->bind_result($id_conductor, $nombres, $apellidos, $dni, $licencia, $telefono, $correo, $direccion, $activo, $foto);

    if ($stmt->fetch()) {
        return array(
            'id' => $id_conductor,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'dni' => $dni,
            'licencia_conducir' => $licencia,
            'telefono' => $telefono,
            'correo' => $correo,
            'direccion' => $direccion,
            'activo' => $activo,
            'foto' => $foto
        );
    }

    return null;
}

/**
 * Guarda o actualiza un conductor
 */
function guardarConductor($conn, $post, $file = null) {
    $id        = (int)$post['id'];
    $nombres   = trim($post['nombres']);
    $apellidos = trim($post['apellidos']);
    $dni       = trim($post['dni']);
    $licencia  = trim($post['licencia_conducir']);
    $telefono  = trim($post['telefono']);
    $correo    = trim($post['correo']);
    $direccion = trim($post['direccion']);
    $activo    = isset($post['activo']) ? 1 : 0;
    $foto      = null;

    // Procesar foto si se subió
    if ($file && isset($file['foto']) && $file['foto']['error'] === UPLOAD_ERR_OK) {
        $nombreTmp = $file['foto']['tmp_name'];
        $nombreFinal = uniqid('foto_') . '.jpg';
        $rutaDestino = __DIR__ . '/../uploads/' . $nombreFinal;
        if (move_uploaded_file($nombreTmp, $rutaDestino)) {
            $foto = $nombreFinal;
        }
    }

    if ($id > 0) {
        // Actualizar
        $sql = "UPDATE conductores SET nombres = ?, apellidos = ?, dni = ?, licencia_conducir = ?, telefono = ?, correo = ?, direccion = ?, activo = ?" . ($foto ? ", foto = ?" : "") . " WHERE id = ?";
        $stmt = prep($conn, $sql);
        if ($foto) {
            $stmt->bind_param("sssssssisi", $nombres, $apellidos, $dni, $licencia, $telefono, $correo, $direccion, $activo, $foto, $id);
        } else {
            $stmt->bind_param("ssssssssi", $nombres, $apellidos, $dni, $licencia, $telefono, $correo, $direccion, $activo, $id);
        }
    } else {
        // Insertar
        $sql = "INSERT INTO conductores (nombres, apellidos, dni, licencia_conducir, telefono, correo, direccion, foto, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = prep($conn, $sql);
        $stmt->bind_param("ssssssssi", $nombres, $apellidos, $dni, $licencia, $telefono, $correo, $direccion, $foto, $activo);
    }

    if (!$stmt->execute()) {
        return "❌ Error al guardar conductor: {$stmt->error}";
    }

    return '';
}

/**
 * Elimina (desactiva) un conductor
 */
function eliminarConductor($conn, $id) {
    $stmt = prep($conn, "UPDATE conductores SET activo = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute() ? '' : "❌ Error al desactivar conductor: {$stmt->error}";
}

/**
 * Restaura (reactiva) un conductor
 */
function restaurarConductor($conn, $id) {
    $stmt = prep($conn, "UPDATE conductores SET activo = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute() ? '' : "❌ Error al restaurar conductor: {$stmt->error}";
}


/**
 * Elimina definitivamente un conductor
 */
function eliminarConductorPermanentemente($conn, $id) {
    $stmt = prep($conn, "DELETE FROM conductores WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute() ? '' : "❌ Error al eliminar conductor: {$stmt->error}";
}


