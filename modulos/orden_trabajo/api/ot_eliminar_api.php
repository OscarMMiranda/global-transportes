<?php
// ======================================================
//  API: ot_eliminar_api.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Eliminación lógica de OT
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================

// ------------------------------
// Validación de sesión
// ------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(array(
        "success" => false,
        "message" => "Sesión no válida"
    ));
    exit;
}

// ------------------------------
// Cargar configuración global
// ------------------------------
require_once __DIR__ . '/../../../includes/config.php';

// ------------------------------
// Conexión a la base de datos
// ------------------------------
$conn = getConnection();
if (!$conn) {

    error_log("[ERP-OT] Error de conexión en API eliminar OT: " . date('Y-m-d H:i:s'));

    echo json_encode(array(
        "success" => false,
        "message" => "Error de conexión al sistema"
    ));
    exit;
}

// ------------------------------
// Validación de método
// ------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array(
        "success" => false,
        "message" => "Método no permitido"
    ));
    exit;
}

// ------------------------------
// Validación de parámetros
// ------------------------------
if (!isset($_POST['id']) || trim($_POST['id']) === '') {
    echo json_encode(array(
        "success" => false,
        "message" => "ID de OT no proporcionado"
    ));
    exit;
}

$id = mysqli_real_escape_string($conn, $_POST['id']);

// ------------------------------
// Eliminación lógica corporativa
// ------------------------------
$sql = "
    UPDATE ordenes_trabajo
    SET deleted_at = NOW(),
        usuario_elimino = '" . $_SESSION['usuario_id'] . "'
    WHERE id = '$id'
    LIMIT 1
";

$ok = mysqli_query($conn, $sql);

// ------------------------------
// Respuesta final
// ------------------------------
if ($ok) {
    echo json_encode(array(
        "success" => true,
        "message" => "Orden eliminada correctamente"
    ));
} else {
    echo json_encode(array(
        "success" => false,
        "message" => mysqli_error($conn)
    ));
}

exit;
