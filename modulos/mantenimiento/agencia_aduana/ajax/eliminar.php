<?php
// archivo: /modulos/mantenimiento/agencia_aduana/ajax/eliminar.php

session_start();

// 🛡️ Diagnóstico y trazabilidad
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
file_put_contents(__DIR__ . '/debug.log', date('Y-m-d H:i:s') . " - POST: " . json_encode($_POST) . "\n", FILE_APPEND);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php';

$conn = getConnection();
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if (!is_object($conn)) {
    error_log("❌ eliminar.php: conexión inválida");
    echo '❌ Error de conexión';
    return;
}

if ($id <= 0) {
    error_log("⚠️ eliminar.php: ID inválido recibido: " . json_encode($_POST));
    echo '❌ ID inválido';
    return;
}

// 🔍 Validar existencia y estado activo
$sqlCheck = "SELECT id FROM agencias_aduanas WHERE id = $id AND estado = 1 LIMIT 1";
$resCheck = $conn->query($sqlCheck);

if (!$resCheck || $resCheck->num_rows === 0) {
    error_log("ℹ️ eliminar.php: registro no encontrado o ya inactivo: ID=$id");
    echo '⚠️ Registro no encontrado o ya eliminado';
    return;
}

// 🧨 Ejecutar eliminación lógica
$sqlDelete = "UPDATE agencias_aduanas SET estado = 0 WHERE id = $id";
$resDelete = $conn->query($sqlDelete);

if ($resDelete) {
    registrarEnHistorial(
        $conn,
        $_SESSION['usuario'],
        "Eliminó agencia aduana (ID: $id)",
        'agencias_aduanas',
        $_SERVER['REMOTE_ADDR']
    );
    error_log("✅ eliminar.php: registro marcado como inactivo: ID=$id");
    echo '✅ Registro eliminado correctamente';
} else {
    error_log("❌ eliminar.php: error al ejecutar UPDATE para ID=$id - " . $conn->error);
    echo '❌ Error al eliminar el registro';
}