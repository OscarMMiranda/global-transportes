<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

$conn = getConnection();
if (!$conn) {
    echo json_encode(["ok" => false, "mensaje" => "Error de conexión."]);
    exit;
}

// ------------------------------------------------------
// CAPTURA
// ------------------------------------------------------
$id       = intval($_POST['id']);
$nombre   = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$usuario  = trim($_POST['usuario']);
$correo   = trim($_POST['correo']);
$rol      = intval($_POST['rol']);
$password = trim($_POST['password']);

$errores = [];

if ($id <= 0) $errores[] = "ID inválido.";
if ($nombre == "") $errores[] = "El nombre es obligatorio.";
if ($apellido == "") $errores[] = "El apellido es obligatorio.";
if ($usuario == "") $errores[] = "El usuario es obligatorio.";
if ($correo == "") $errores[] = "El correo es obligatorio.";
if ($rol <= 0) $errores[] = "Debe seleccionar un rol.";

if (!empty($errores)) {
    echo json_encode(["ok" => false, "errores" => $errores]);
    exit;
}

// ------------------------------------------------------
// SQL DINÁMICO
// ------------------------------------------------------
$sql = "UPDATE usuarios SET 
            nombre = ?, 
            apellido = ?, 
            usuario = ?, 
            correo = ?, 
            rol = ?";

$paramTypes = "ssssi";
$params = array($nombre, $apellido, $usuario, $correo, $rol);

// Si hay contraseña → agregarla
if ($password !== "") {
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $sql .= ", contrasena = ?";
    $paramTypes .= "s";
    $params[] = $passwordHash;
}

$sql .= " WHERE id = ?";
$paramTypes .= "i";
$params[] = $id;

// ------------------------------------------------------
// PREPARAR Y EJECUTAR (PHP 5.6)
// ------------------------------------------------------
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["ok" => false, "mensaje" => "Error SQL: ".$conn->error]);
    exit;
}

// bind_param dinámico
$bindNames[] = $paramTypes;
for ($i = 0; $i < count($params); $i++) {
    $bindNames[] = &$params[$i];
}

call_user_func_array(array($stmt, 'bind_param'), $bindNames);

if ($stmt->execute()) {
    echo json_encode(["ok" => true, "mensaje" => "Usuario actualizado correctamente."]);
} else {
    echo json_encode(["ok" => false, "mensaje" => "Error al actualizar: ".$stmt->error]);
}

$stmt->close();
$conn->close();