<?php
// archivo: /modulos/orden_trabajo/controllers/ClienteController.php

// Inicia sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexión centralizada
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';

/**
 * Devuelve un array de clientes activos con ['id' => ..., 'nombre' => ...]
 * Compatible con vistas y formularios. No depende del método HTTP.
 */
function obtenerClientesActivos() {
    global $conn;

    if (!$conn) {
        error_log("❌ Conexión no disponible en ClienteController");
        return [];
    }

    $clientes = [];
    $sql = "SELECT id, nombre FROM clientes WHERE estado = 'activo' ORDER BY nombre ASC";
    $stmt = $conn->prepare($sql);

    if ($stmt && $stmt->execute()) {
        $resultado = $stmt->get_result();
        while ($fila = $resultado->fetch_assoc()) {
            $clientes[] = $fila;
        }
        $stmt->close();
    } else {
        error_log("⚠️ Error al ejecutar consulta de clientes activos");
    }

    return $clientes;
}