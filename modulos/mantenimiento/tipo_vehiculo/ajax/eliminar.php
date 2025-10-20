<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/ajax/eliminar.php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error_log.txt');

require_once __DIR__ . '/../../../../includes/config.php';
require_once __DIR__ . '/../modelo/TipoVehiculoModel.php';

$conn = getConnection();
$modelo = new TipoVehiculoModel($conn);

// Validación defensiva del ID recibido
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

// ⚠️ Reemplazá esto por tu sistema real de sesión
$usuarioId = 1;

if ($id > 0) {
    try {
        $modelo->eliminar($id, $usuarioId);
        echo 'OK';
    } catch (Exception $e) {
        error_log("❌ Error al eliminar ID {$id}: " . $e->getMessage());
        echo 'ERROR';
    }
} else {
    error_log("⚠️ ID inválido recibido para eliminar: " . $id);
    echo 'ERROR';
}

$conn->close();