<?php
// archivo: /modulos/seguridad/roles/acciones/eliminar_rol.php
require_once __DIR__ . '/../../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';

requirePermiso("roles", "eliminar");

if (!isset($_POST['id'])) {
    echo json_encode([
        "ok" => false,
        "tipo" => "error",
        "mensaje" => "ID no recibido."
    ]);
    exit;
}

$id = intval($_POST['id']);

if ($id <= 0) {
    echo json_encode([
        "ok" => false,
        "tipo" => "warning",
        "mensaje" => "ID inválido."
    ]);
    exit;
}

$conn = getConnection();

// Obtener nombre del rol
$sqlRol = "SELECT nombre FROM roles WHERE id = ?";
$stmtRol = $conn->prepare($sqlRol);
$stmtRol->bind_param("i", $id);
$stmtRol->execute();
$resRol = $stmtRol->get_result();

if ($resRol->num_rows === 0) {
    echo json_encode([
        "ok" => false,
        "tipo" => "error",
        "mensaje" => "El rol no existe."
    ]);
    exit;
}

$rowRol = $resRol->fetch_assoc();
$nombreRol = $rowRol['nombre'];

// Verificar si está asignado a usuarios
$sqlCheck = "SELECT COUNT(*) AS total FROM usuarios WHERE rol_id = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("i", $id);
$stmtCheck->execute();
$resCheck = $stmtCheck->get_result()->fetch_assoc();

if ($resCheck['total'] > 0) {
    echo json_encode([
        "ok" => false,
        "tipo" => "warning",
        "mensaje" => "No se puede eliminar: el rol está asignado a usuarios."
    ]);
    exit;
}

// Eliminar
$sql = "DELETE FROM roles WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$ok = $stmt->execute();

// Auditoría
if ($ok && function_exists('registrarLog')) {
    registrarLog(
        "roles",
        "eliminar",
        "Se eliminó el rol '$nombreRol' (ID: $id)"
    );
}

echo json_encode([
    "ok" => $ok,
    "tipo" => $ok ? "success" : "error",
    "mensaje" => $ok ? "Rol eliminado correctamente." : "Error al eliminar el rol."
]);