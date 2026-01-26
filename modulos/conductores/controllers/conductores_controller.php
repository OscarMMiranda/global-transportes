<?php
// archivo: /modulos/conductores/controllers/conductores_controller.php

/**
 * Prepara un statement seguro
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
 * Lista conductores según estado (activo / inactivo)
 */
function listarConductores($conn, $estado = 'activo') {

    $sql = "SELECT 
                id, nombres, apellidos, dni, licencia_conducir, telefono, correo, direccion,
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

    $stmt->bind_result(
        $id, $nombres, $apellidos, $dni, $licencia, $telefono, $correo,
        $direccion, $distrito_id, $provincia_id, $departamento_id,
        $activo, $created_at, $foto
    );

    $rows = [];

    while ($stmt->fetch()) {
        $rows[] = [
            'id'                => $id,
            'nombres'           => $nombres,
            'apellidos'         => $apellidos,
            'dni'               => $dni,
            'licencia_conducir' => $licencia,
            'telefono'          => $telefono,
            'correo'            => $correo,
            'direccion'         => $direccion,
            'distrito_id'       => $distrito_id,
            'provincia_id'      => $provincia_id,
            'departamento_id'   => $departamento_id,
            'activo'            => (int)$activo,
            'created_at'        => $created_at,
            'foto'              => $foto
        ];
    }

    $stmt->close();
    return $rows;
}

/**
 * Obtiene un conductor por ID
 */
function obtenerConductorPorId($conn, $id) {

    $stmt = prep($conn, "
        SELECT id, nombres, apellidos, dni, licencia_conducir, telefono, correo, direccion,
               distrito_id, provincia_id, departamento_id, activo, created_at, foto
        FROM conductores
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        error_log("❌ Error en execute: " . $stmt->error);
        return null;
    }

    $stmt->bind_result(
        $id_c, $nombres, $apellidos, $dni, $licencia, $telefono, $correo,
        $direccion, $distrito_id, $provincia_id, $departamento_id,
        $activo, $created_at, $foto
    );

    if ($stmt->fetch()) {

        $data = [
            'id'                => $id_c,
            'nombres'           => $nombres,
            'apellidos'         => $apellidos,
            'dni'               => $dni,
            'licencia_conducir' => $licencia,
            'telefono'          => $telefono,
            'correo'            => $correo,
            'direccion'         => $direccion,
            'distrito_id'       => $distrito_id,
            'provincia_id'      => $provincia_id,
            'departamento_id'   => $departamento_id,
            'activo'            => (int)$activo,
            'created_at'        => $created_at,
            'foto'              => $foto
        ];

        $stmt->close();
        return $data;
    }

    $stmt->close();
    return null;
}

/**
 * Desactiva (soft delete)
 */
function eliminarConductor($conn, $id) {
    $stmt = prep($conn, "UPDATE conductores SET activo = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok ? '' : "❌ Error al desactivar conductor: {$stmt->error}";
}

/**
 * Restaura conductor
 */
function restaurarConductor($conn, $id) {
    $stmt = prep($conn, "UPDATE conductores SET activo = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok ? '' : "❌ Error al restaurar conductor: {$stmt->error}";
}

/**
 * Eliminación definitiva
 */
function eliminarConductorPermanentemente($conn, $id) {
    $stmt = prep($conn, "DELETE FROM conductores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok ? '' : "❌ Error al eliminar conductor: {$stmt->error}";
}