<?php
// archivo: /modulos/mantenimiento/agencia_aduana/modelo/agencia_estado.php

require_once __DIR__ . '/../../../../includes/conexion.php';
require_once __DIR__ . '/../../../../includes/funciones.php';

if (!isset($_SESSION)) {
    session_start();
}

/**
 * Eliminación lógica de agencia aduana.
 * @param int $id ID de la agencia
 */
function eliminarAgencia($id) {
    global $conn;
    $id = (int)$id;
    if ($id <= 0 || !$conn) return;

    $stmt = $conn->prepare("UPDATE agencias_aduanas SET estado = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        registrarEnHistorial(
            $conn,
            $_SESSION['usuario'],
            "Eliminó agencia aduana (ID: $id)",
            'agencias_aduanas',
            $_SERVER['REMOTE_ADDR']
        );
    } else {
        error_log("❌ Error al eliminar agencia ID: $id - " . $conn->error);
    }
}

/**
 * Reactivar agencia aduana.
 * @param int $id ID de la agencia
 */
function reactivarAgencia($id) {
    global $conn;
    $id = (int)$id;
    if ($id <= 0 || !$conn) return;

    $stmt = $conn->prepare("UPDATE agencias_aduanas SET estado = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        registrarEnHistorial(
            $conn,
            $_SESSION['usuario'],
            "Reactivó agencia aduana (ID: $id)",
            'agencias_aduanas',
            $_SERVER['REMOTE_ADDR']
        );
    } else {
        error_log("❌ Error al reactivar agencia ID: $id - " . $conn->error);
    }
}

