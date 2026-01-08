<?php
// auditoria.php — Registro profesional de auditoría

/**
 * Registra una acción en la tabla auditoria.
 *
 * @param mysqli  $conn         Conexión activa
 * @param string  $modulo       Nombre del módulo (usuarios, ventas, clientes, etc.)
 * @param string  $accion       Acción realizada (crear, editar, eliminar, etc.)
 * @param int     $registroId   ID del registro afectado
 * @param int     $usuarioId    ID del usuario que ejecuta la acción
 * @param mixed   $antes        Datos antes del cambio (array o null)
 * @param mixed   $despues      Datos después del cambio (array o null)
 * @return bool
 */
function registrarAuditoria($conn, $modulo, $accion, $registroId, $usuarioId, $antes = null, $despues = null)
{
    if (!$conn || !$modulo || !$accion || !$registroId || !$usuarioId) {
        error_log("❌ registrarAuditoria: parámetros incompletos");
        return false;
    }

    // Convertir arrays a JSON si es necesario
    if (is_array($antes)) {
        $antes = json_encode($antes, JSON_UNESCAPED_UNICODE);
    }
    if (is_array($despues)) {
        $despues = json_encode($despues, JSON_UNESCAPED_UNICODE);
    }

    $sql = "INSERT INTO auditoria 
            (modulo, accion, registro_id, usuario_id, valores_antes, valores_despues, fecha)
            VALUES (?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("❌ registrarAuditoria: error prepare - " . $conn->error);
        return false;
    }

    $stmt->bind_param("ssiiss", 
        $modulo,
        $accion,
        $registroId,
        $usuarioId,
        $antes,
        $despues
    );

    $ok = $stmt->execute();

    if (!$ok) {
        error_log("❌ registrarAuditoria: error execute - " . $stmt->error);
    } else {
        error_log("✅ Auditoría registrada: $modulo / $accion / ID $registroId");
    }

    $stmt->close();
    return $ok;
}