<?php
// archivo: /modulos/orden_trabajo/controllers/ClienteController.php

// ===============================
// 🔧 Iniciar sesión si no existe
// ===============================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ===============================
// 🔧 Conexión centralizada
// ===============================
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
$conn = getConnection();

/**
 * ============================================================
 *  🔵 obtenerClientesActivos()
 *  Devuelve un array:
 *  [
 *      ["id" => 1, "nombre" => "Cliente X"],
 *      ["id" => 2, "nombre" => "Cliente Y"]
 *  ]
 *
 *  - Compatible con PHP 5.6
 *  - Compatible con AJAX y vistas
 *  - No depende del método HTTP
 *  - No usa globales (solo si la conexión existe)
 * ============================================================
 */
function obtenerClientesActivos()
{
    global $conn;

    if (!$conn) {
        error_log("❌ Conexión no disponible en ClienteController");
        return array();
    }

    $clientes = array();

    $sql = "SELECT id, nombre 
            FROM clientes 
            WHERE estado = 'activo'
            ORDER BY nombre ASC";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("⚠️ Error preparando consulta de clientes: " . $conn->error);
        return array();
    }

    if (!$stmt->execute()) {
        error_log("⚠️ Error ejecutando consulta de clientes: " . $stmt->error);
        return array();
    }

    $res = $stmt->get_result();

    while ($fila = $res->fetch_assoc()) {
        $clientes[] = $fila;
    }

    $stmt->close();

    return $clientes;
}

/**
 * ============================================================
 *  🔵 MODO AJAX (opcional)
 *  Si se llama con:
 *      /ClienteController.php?ajax=1
 *  devuelve JSON con los clientes activos.
 * ============================================================
 */
if (isset($_GET['ajax']) && $_GET['ajax'] == "1") {

    $clientes = obtenerClientesActivos();

    echo json_encode(array(
        "estado" => "ok",
        "data"   => $clientes
    ));
    exit;
}
