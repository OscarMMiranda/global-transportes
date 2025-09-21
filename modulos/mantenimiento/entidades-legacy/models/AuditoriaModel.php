<?php
// AuditoriaModel.php

function registrarAuditoria($conn, $datos) {
    if (!($conn instanceof mysqli)) {
        error_log("❌ Conexión inválida en registrarAuditoria");
        return false;
    }

    // Validación defensiva de campos requeridos
    $tabla         = isset($datos['tabla'])         ? trim($datos['tabla'])         : '';
    $accion        = isset($datos['accion'])        ? trim($datos['accion'])        : '';
    $id_registro   = isset($datos['id_registro'])   ? intval($datos['id_registro']) : 0;
    $usuario       = isset($datos['usuario'])       ? trim($datos['usuario'])       : 'sistema';
    $antes         = isset($datos['antes'])         ? trim($datos['antes'])         : '';
    $despues       = isset($datos['despues'])       ? trim($datos['despues'])       : '';

    if ($tabla === '' || $accion === '' || $id_registro <= 0) {
        error_log("❌ Datos incompletos para auditoría");
        return false;
    }

    $stmt = $conn->prepare("
        INSERT INTO auditoria (
            tabla,
            accion,
            id_registro,
            usuario,
            fecha,
            valores_antes,
            valores_despues
        ) VALUES (?, ?, ?, ?, NOW(), ?, ?)
    ");

    if (!$stmt) {
        error_log("❌ Error al preparar INSERT auditoría: " . $conn->error);
        return false;
    }

    $stmt->bind_param(
        'ssisss',
        $tabla,
        $accion,
        $id_registro,
        $usuario,
        $antes,
        $despues
    );

    $exito = $stmt->execute();
    if (!$exito) {
        error_log("❌ Error al ejecutar auditoría: " . $stmt->error);
    } else {
        error_log("📝 Auditoría registrada: $accion en $tabla (ID $id_registro)");
    }

    $stmt->close();
    return $exito;
}