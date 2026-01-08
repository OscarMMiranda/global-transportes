<?php
// archivo: /modulos/conductores/controllers/conductores_controller.php

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
function listarConductores($conn, $estado = 'activo') {
    $sql = "SELECT id, nombres, apellidos, dni, licencia_conducir, telefono, correo, direccion,
                   distrito_id, provincia_id, departamento_id, activo, created_at, foto
            FROM conductores";

    if ($estado === 'activo') {
        $sql .= " WHERE activo = 1";
    } elseif ($estado === 'inactivo') {
        $sql .= " WHERE activo = 0";
    }
    $sql .= " ORDER BY apellidos, nombres";

    $stmt = prep($conn, $sql);
    if (!$stmt->execute()) {
        throw new Exception("❌ Error en execute(): {$stmt->error}");
    }

    $stmt->bind_result($id, $nombres, $apellidos, $dni, $licencia, $telefono, $correo,
                       $direccion, $distrito_id, $provincia_id, $departamento_id,
                       $activo, $created_at, $foto);

    $rows = [];
    while ($stmt->fetch()) {
        $rows[] = [
            'id'             => $id,
            'nombres'        => $nombres,
            'apellidos'      => $apellidos,
            'dni'            => $dni,
            'licencia_conducir' => $licencia,
            'telefono'       => $telefono,
            'correo'         => $correo,
            'direccion'      => $direccion,
            'distrito_id'    => $distrito_id,
            'provincia_id'   => $provincia_id,
            'departamento_id'=> $departamento_id,
            'activo'         => (int)$activo,
            'created_at'     => $created_at,
            'foto'           => $foto
        ];
    }
    $stmt->close();
    return $rows;
}

/**
 * Obtiene un conductor por ID
 */
function obtenerConductorPorId($conn, $id) {
    $stmt = prep($conn, "SELECT id, nombres, apellidos, dni, licencia_conducir, telefono, correo, direccion,
                                distrito_id, provincia_id, departamento_id, activo, created_at, foto
                         FROM conductores WHERE id = ?");
    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        error_log("❌ Error en execute: " . $stmt->error);
        return null;
    }

    $stmt->bind_result($id_c, $nombres, $apellidos, $dni, $licencia, $telefono, $correo,
                       $direccion, $distrito_id, $provincia_id, $departamento_id, $activo,
                       $created_at, $foto);

    if ($stmt->fetch()) {
        $data = [
            'id'             => $id_c,
            'nombres'        => $nombres,
            'apellidos'      => $apellidos,
            'dni'            => $dni,
            'licencia_conducir' => $licencia,
            'telefono'       => $telefono,
            'correo'         => $correo,
            'direccion'      => $direccion,
            'distrito_id'    => $distrito_id,
            'provincia_id'   => $provincia_id,
            'departamento_id'=> $departamento_id,
            'activo'         => (int)$activo,
            'created_at'     => $created_at,
            'foto'           => $foto
        ];
        $stmt->close();
        return $data;
    }

    $stmt->close();
    return null;
}

/**
 * Guarda o actualiza un conductor
 */
function guardarConductor($conn, $post, $file = null) {
    $id        = isset($post['id']) ? (int)$post['id'] : 0;
    $nombres   = isset($post['nombres']) ? trim($post['nombres']) : '';
    $apellidos = isset($post['apellidos']) ? trim($post['apellidos']) : '';
    $dni       = isset($post['dni']) ? trim($post['dni']) : '';
    $licencia  = isset($post['licencia_conducir']) ? trim($post['licencia_conducir']) : '';
    $telefono  = isset($post['telefono']) ? trim($post['telefono']) : '';
    $correo    = isset($post['correo']) ? trim($post['correo']) : '';
    $direccion = isset($post['direccion']) ? trim($post['direccion']) : '';
    $activo    = isset($post['activo']) ? 1 : 0;
    $foto      = '';

    if ($file && isset($file['foto']) && $file['foto']['error'] === UPLOAD_ERR_OK) {
        $nombreTmp   = $file['foto']['tmp_name'];
        $nombreFinal = uniqid('foto_') . '.jpg';
        $rutaDestino = __DIR__ . '/../uploads/' . $nombreFinal;
        if (move_uploaded_file($nombreTmp, $rutaDestino)) {
            $foto = $nombreFinal;
        }
    }

  if ($id > 0) {
    if ($foto !== '') {
        $sql = "UPDATE conductores 
                SET nombres=?, apellidos=?, dni=?, licencia_conducir=?, telefono=?, correo=?, direccion=?, activo=?, foto=? 
                WHERE id=?";
        $stmt = prep($conn, $sql);
        $stmt->bind_param("ssssssssis", $nombres, $apellidos, $dni, $licencia, $telefono,
                          $correo, $direccion, $activo, $foto, $id);
    } else {
        $sql = "UPDATE conductores 
                SET nombres=?, apellidos=?, dni=?, licencia_conducir=?, telefono=?, correo=?, direccion=?, activo=? 
                WHERE id=?";
        $stmt = prep($conn, $sql);
        $stmt->bind_param("ssssssssi", $nombres, $apellidos, $dni, $licencia, $telefono,
                          $correo, $direccion, $activo, $id);
    }
} else {
    $sql = "INSERT INTO conductores (nombres, apellidos, dni, licencia_conducir, telefono, correo, direccion, foto, activo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = prep($conn, $sql);
    $stmt->bind_param("ssssssssi", $nombres, $apellidos, $dni, $licencia, $telefono,
                      $correo, $direccion, $foto, $activo);
}


    if (!$stmt->execute()) {
        error_log("❌ Error al guardar conductor: {$stmt->error}");
        return "❌ Error al guardar conductor: {$stmt->error}";
    }

    $stmt->close();
    return '';
}

/**
 * Elimina (desactiva) un conductor
 */
function eliminarConductor($conn, $id) {
    $stmt = prep($conn, "UPDATE conductores SET activo = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok ? '' : "❌ Error al desactivar conductor: {$stmt->error}";
}

/**
 * Restaura (reactiva) un conductor
 */
function restaurarConductor($conn, $id) {
    $stmt = prep($conn, "UPDATE conductores SET activo = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok ? '' : "❌ Error al restaurar conductor: {$stmt->error}";
}

/**
 * Elimina definitivamente un conductor
 */
function eliminarConductorPermanentemente($conn, $id) {
    $stmt = prep($conn, "DELETE FROM conductores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok ? '' : "❌ Error al eliminar conductor: {$stmt->error}";
}