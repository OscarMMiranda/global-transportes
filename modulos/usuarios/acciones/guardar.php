<?php
// archivo: /modulos/usuarios/acciones/guardar.php

header('Content-Type: application/json');

ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';
require_once INCLUDES_PATH . '/funciones.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/auditoria.php';
require_once __DIR__ . '/../controllers/usuarios_controller.php';

// Permiso
try {
    requirePermiso('usuarios', 'crear');
} catch (Exception $e) {
    echo json_encode(["ok" => false, "errores" => [$e->getMessage()]]);
    exit;
}

// Captura POST
$nombre   = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$usuario  = trim($_POST['usuario']);
$correo   = trim($_POST['correo']);
$rol      = intval($_POST['rol']);
$password = trim($_POST['password']);

$errores = [];

if ($nombre === '')   $errores[] = "El nombre es obligatorio.";
if ($apellido === '') $errores[] = "El apellido es obligatorio.";
if ($usuario === '')  $errores[] = "El usuario es obligatorio.";
if ($correo === '')   $errores[] = "El correo es obligatorio.";
if ($password === '') $errores[] = "La contraseña es obligatoria.";
if ($rol <= 0)        $errores[] = "Debe seleccionar un rol válido.";

if (!empty($errores)) {
    echo json_encode(["ok" => false, "errores" => $errores]);
    exit;
}

$conn = getConnection();

// Validar rol existente (PHP 5.6 sin get_result)
$sqlCheckRol = "SELECT id FROM roles WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sqlCheckRol);
$stmt->bind_param("i", $rol);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(["ok" => false, "errores" => ["El rol seleccionado no existe."]]);
    exit;
}

// Hash
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// Insertar usuario
$sqlInsert = "
    INSERT INTO usuarios (nombre, apellido, usuario, correo, rol, contrasena, eliminado)
    VALUES (?, ?, ?, ?, ?, ?, 0)
";

$stmt = $conn->prepare($sqlInsert);

// TIPOS CORRECTOS: s s s s i s
$stmt->bind_param("ssssis",
    $nombre,
    $apellido,
    $usuario,
    $correo,
    $rol,
    $passwordHash
);

if (!$stmt->execute()) {
    echo json_encode(["ok" => false, "errores" => ["Error al crear usuario: " . $stmt->error]]);
    exit;
}

$nuevoId = $stmt->insert_id;

// Auditoría
$datosNuevos = [
    "nombre"   => $nombre,
    "apellido" => $apellido,
    "usuario"  => $usuario,
    "correo"   => $correo,
    "rol"      => $rol
];

registrarAuditoria(
    $conn,
    "usuarios",
    "crear",
    $nuevoId,
    $_SESSION['id'],
    null,
    json_encode($datosNuevos)
);

echo json_encode(["ok" => true, "mensaje" => "Usuario creado correctamente."]);
exit;