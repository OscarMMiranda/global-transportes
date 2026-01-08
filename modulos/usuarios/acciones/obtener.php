<?php
// archivo: /modulos/usuarios/acciones/obtener.php
// --------------------------------------------------------------
// Devuelve datos de un usuario + roles disponibles
// --------------------------------------------------------------

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once __DIR__ . '/../controllers/usuarios_controller.php';

// Validar ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "ID inválido",
        "post" => $_POST
    ]);
    exit;
}

$id = intval($_POST['id']);

// Conexión
$conn = getConnection();
if (!$conn) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Error de conexión"
    ]);
    exit;
}

// Obtener usuario
$usuario = obtenerUsuario($conn, $id);
if (!$usuario) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Usuario no encontrado"
    ]);
    exit;
}

// Obtener roles
$roles = obtenerRoles($conn);

// Respuesta final
echo json_encode([
    "ok" => true,
    "data" => $usuario,
    "roles" => $roles
]);