<?php
// archivo: /modulos/seguridad/roles/acciones/agregar_rol.php
require_once __DIR__ . '/../../../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';

requirePermiso("roles", "crear");

if (!isset($_POST['nombre'])) {
    echo json_encode([
        "ok" => false,
        "tipo" => "error",
        "mensaje" => "Nombre de rol no recibido."
    ]);
    exit;
}

$nombre = trim($_POST['nombre']);

if ($nombre === "") {
    echo json_encode([
        "ok" => false,
        "tipo" => "warning",
        "mensaje" => "El nombre del rol no puede estar vacío."
    ]);
    exit;
}

$conn = getConnection();

// Validar duplicado
$sqlCheck = "SELECT COUNT(*) AS total FROM roles WHERE nombre = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("s", $nombre);
$stmtCheck->execute();
$resCheck = $stmtCheck->get_result()->fetch_assoc();

if ($resCheck['total'] > 0) {
    echo json_encode([
        "ok" => false,
        "tipo" => "warning",
        "mensaje" => "Ya existe un rol con ese nombre."
    ]);
    exit;
}

// Insertar
$sql = "INSERT INTO roles (nombre) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nombre);
$ok = $stmt->execute();
$idNuevo = $ok ? $stmt->insert_id : null;

// Auditoría simple (si tienes función, úsala aquí)
if ($ok && function_exists('registrarLog')) {
    registrarLog("roles", "crear", "Se creó el rol '$nombre' (ID: $idNuevo)");
}

echo json_encode([
    "ok" => $ok,
    "tipo" => $ok ? "success" : "error",
    "mensaje" => $ok ? "Rol creado correctamente." : "Error al crear el rol.",
    "id" => $idNuevo
]);

