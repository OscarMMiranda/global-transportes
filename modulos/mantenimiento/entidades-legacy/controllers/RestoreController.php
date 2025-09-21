<?php
// archivo  : /modulos/mantenimiento/entidades/controllers/RestoreController.php

// 1) Cargar configuración y conexión
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// 2) Validar ID recibido
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0 && ($conn instanceof mysqli)) {
    // 3) Restaurar entidad (cambiar estado a 'activo')
    $stmt = $conn->prepare("UPDATE entidades SET estado = 'activo', fecha_modificacion = NOW() WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // 4) Log de auditoría
        error_log("♻️ Entidad restaurada: ID $id");
    } else {
        error_log("❌ Error en prepare() al restaurar entidad ID $id");
    }
} else {
    error_log("⚠️ ID inválido o conexión fallida en RestoreController");
}

// 5) Redirigir al listado principal
header("Location: /modulos/mantenimiento/entidades/index.php?action=list");
exit;