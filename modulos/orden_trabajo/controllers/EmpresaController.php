<?php
// archivo: /modulos/orden_trabajo/controllers/EmpresaController.php

// Inicia sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexión centralizada
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';

/**
 * Devuelve un array de empresas activas con ['id' => ..., 'nombre' => ...]
 * Compatible con vistas y formularios. No depende del método HTTP.
 */
function obtenerEmpresasActivas() {
    global $conn;

    if (!$conn) {
        error_log("❌ Conexión no disponible en EmpresaController");
        return [];
    }

    $empresa = [];
    $sql = "SELECT id, razon_social FROM empresa ORDER BY razon_social ASC";
    $stmt = $conn->prepare($sql);

    if ($stmt && $stmt->execute()) {
        $resultado = $stmt->get_result();
        while ($fila = $resultado->fetch_assoc()) {
            $empresa[] = $fila;
        }
        $stmt->close();
    } else {
        error_log("⚠️ Error al ejecutar consulta de empresa activos");
    }

    return $empresa;
}