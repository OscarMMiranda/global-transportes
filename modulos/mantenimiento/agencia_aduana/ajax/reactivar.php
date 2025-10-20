<?php
// archivo: /modulos/mantenimiento/agencia_aduana/ajax/reactivar.php

session_start();

// 🔐 Blindaje extremo y trazabilidad
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
    error_log("❌ reactivar.php: conexión inválida");
    echo '❌ Error de conexión';
    return;
}

if ($id <= 0) {
    error_log("⚠️ reactivar.php: ID inválido recibido: " . json_encode($_POST));
    echo '❌ ID inválido';
    return;
}

// 🔍 Validar existencia del registro inactivo
$sqlCheck = "SELECT id FROM agencias_aduanas WHERE id = $id AND estado = 0 LIMIT 1";
$resCheck = $conn->query($sqlCheck);

if (!$resCheck || $resCheck->num_rows === 0) {
    error_log("ℹ️ reactivar.php: registro no encontrado o ya activo: ID=$id");
    echo '⚠️ Registro no encontrado o ya activo';
    return;
}

// ♻️ Ejecutar reactivación lógica
$sqlReactivate = "UPDATE agencias_aduanas SET estado = 1 WHERE id = $id";
$resReactivate = $conn->query($sqlReactivate);

if ($resReactivate) {
    registrarEnHistorial(
        $conn,
        $_SESSION['usuario'],
        "Reactivó agencia aduana (ID: $id)",
        'agencias_aduanas',
        $_SERVER['REMOTE_ADDR']
    );
    error_log("✅ reactivar.php: registro reactivado correctamente: ID=$id");
    echo '✅ Agencia reactivada correctamente';
} else {
    error_log("❌ reactivar.php: error al ejecutar UPDATE para ID=$id - " . $conn->error);
    echo '❌ Error al reactivar la agencia';
}