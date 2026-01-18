<?php
// archivo: /modulos/seguridad/roles/acciones/editar_rol.php

require_once __DIR__ . '/../../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';

requirePermiso("roles", "editar");

if (!isset($_POST['id']) || !isset($_POST['nombre'])) {
    echo json_encode([
        "ok" => false,
        "tipo" => "error",
        "mensaje" => "Datos incompletos."
    ]);
    exit;
}

$id = intval($_POST['id']);
$nombre = trim($_POST['nombre']);

if ($id <= 0 || $nombre === "") {
    echo json_encode([
        "ok" => false,
        "tipo" => "warning",
        "mensaje" => "Datos inválidos."
    ]);
    exit;
}

$conn = getConnection();

// Obtener nombre anterior
$sqlOld = "SELECT nombre FROM roles WHERE id = ?";
$stmtOld = $conn->prepare($sqlOld);
$stmtOld->bind_param("i", $id);
$stmtOld->execute();
$resOld = $stmtOld->get_result();

if ($resOld->num_rows === 0) {
    echo json_encode([
        "ok" => false,
        "tipo" => "error",
        "mensaje" => "El rol no existe."
    ]);
    exit;
}

$rowOld = $resOld->fetch_assoc();
$nombreAnterior = $rowOld['nombre'];

// Validar duplicado
$sqlCheck = "SELECT COUNT(*) AS total FROM roles WHERE nombre = ? AND id != ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("si", $nombre, $id);
$stmtCheck->execute();
$resCheck = $stmtCheck->get_result()->fetch_assoc();

if ($resCheck['total'] > 0) {
    echo json_encode([
        "ok" => false,
        "tipo" => "warning",
        "mensaje" => "Ya existe otro rol con ese nombre."
    ]);
    exit;
}

// Actualizar
$sql = "UPDATE roles SET nombre = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $nombre, $id);
$ok = $stmt->execute();

// Auditoría
if ($ok && function_exists('registrarLog')) {
    registrarLog(
        "roles",
        "editar",
        "Se cambió el rol ID $id de '$nombreAnterior' a '$nombre'"
    );
}

echo json_encode([
    "ok" => $ok,
    "tipo" => $ok ? "success" : "error",
    "mensaje" => $ok ? "Rol actualizado correctamente." : "Error al actualizar el rol.",
    "id" => $id
]);