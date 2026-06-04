<?php
// archivo: /modulos/orden_trabajo/controllers/TipoOTController.php

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
 *  🔵 obtenerTiposOT()
 *  Devuelve un array:
 *  [
 *      ["id" => 1, "codigo" => "IMP", "nombre" => "Importación"],
 *      ["id" => 2, "codigo" => "EXP", "nombre" => "Exportación"]
 *  ]
 *
 *  - Compatible con PHP 5.6
 *  - Compatible con AJAX y vistas
 *  - No depende del método HTTP
 * ============================================================
 */
function obtenerTiposOT()
{
    global $conn;

    if (!$conn) {
        error_log("❌ Conexión no disponible en TipoOTController");
        return array();
    }

    $tipos = array();

    $sql = "SELECT id, codigo, nombre 
            FROM tipo_ot 
            WHERE estado = 'activo'
            ORDER BY nombre ASC";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("❌ Error preparando consulta de tipos OT: " . $conn->error);
        return array();
    }

    if (!$stmt->execute()) {
        error_log("⚠️ Error ejecutando consulta de tipos OT: " . $stmt->error);
        return array();
    }

    $res = $stmt->get_result();

    while ($fila = $res->fetch_assoc()) {
        $tipos[] = $fila;
    }

    $stmt->close();

    return $tipos;
}

/**
 * ============================================================
 *  🔵 MODO AJAX (opcional)
 *  Si se llama con:
 *      /TipoOTController.php?ajax=1
 *  devuelve JSON con los tipos de OT activos.
 * ============================================================
 */
if (isset($_GET['ajax']) && $_GET['ajax'] == "1") {

    $tipos = obtenerTiposOT();

    echo json_encode(array(
        "estado" => "ok",
        "data"   => $tipos
    ));
    exit;
}
