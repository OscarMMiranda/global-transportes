<?php
// archivo: /modulos/orden_trabajo/controllers/EmpresaController.php

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
 *  🔵 obtenerEmpresasActivas()
 *  Devuelve un array:
 *  [
 *      ["id" => 1, "razon_social" => "Empresa X"],
 *      ["id" => 2, "razon_social" => "Empresa Y"]
 *  ]
 *
 *  - Compatible con PHP 5.6
 *  - Compatible con AJAX y vistas
 *  - No depende del método HTTP
 * ============================================================
 */
function obtenerEmpresasActivas()
{
    global $conn;

    if (!$conn) {
        error_log("❌ Conexión no disponible en EmpresaController");
        return array();
    }

    $empresas = array();

    $sql = "SELECT id, razon_social 
            FROM empresa 
            WHERE estado = 'activo'
            ORDER BY razon_social ASC";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("⚠️ Error preparando consulta de empresas: " . $conn->error);
        return array();
    }

    if (!$stmt->execute()) {
        error_log("⚠️ Error ejecutando consulta de empresas: " . $stmt->error);
        return array();
    }

    $res = $stmt->get_result();

    while ($fila = $res->fetch_assoc()) {
        $empresas[] = $fila;
    }

    $stmt->close();

    return $empresas;
}

/**
 * ============================================================
 *  🔵 MODO AJAX (opcional)
 *  Si se llama con:
 *      /EmpresaController.php?ajax=1
 *  devuelve JSON con las empresas activas.
 * ============================================================
 */
if (isset($_GET['ajax']) && $_GET['ajax'] == "1") {

    $empresas = obtenerEmpresasActivas();

    echo json_encode(array(
        "estado" => "ok",
        "data"   => $empresas
    ));
    exit;
}
