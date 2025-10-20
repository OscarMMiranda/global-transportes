<?php
// archivo: /admin/users/ajax/ajax_crear_usuario.php

session_start();
require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

// Activar depuración defensiva
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log_crear.txt');
error_reporting(E_ALL);

// Validar sesión y rol
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
  echo json_encode(['estado' => 'error', 'mensaje' => 'Acceso no autorizado']);
  exit;
}

// Validar datos recibidos
$nombre   = isset($_POST['nombre'])   ? trim($_POST['nombre'])   : '';
$apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
$usuario  = isset($_POST['usuario'])  ? trim($_POST['usuario'])  : '';
$correo   = isset($_POST['correo'])   ? trim($_POST['correo'])   : '';
$clave    = isset($_POST['clave'])    ? $_POST['clave']          : '';
$rol      = isset($_POST['rol'])      ? intval($_POST['rol'])    : 0;

if (!$nombre || !$apellido || !$usuario || !$correo || !$clave || $rol <= 0) {
  echo json_encode(['estado' => 'error', 'mensaje' => '❌ Todos los campos son obligatorios']);
  exit;
}

// Validar duplicados
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ? OR correo = ?");
$stmt->bind_param("ss", $usuario, $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
  echo json_encode(['estado' => 'error', 'mensaje' => '❌ El usuario o correo ya existe']);
  $stmt->close();
  exit;
}
$stmt->close();

// Hashear contraseña
$hash = password_hash($clave, PASSWORD_DEFAULT);

// Insertar usuario
$stmt = $conn->prepare("
  INSERT INTO usuarios (nombre, apellido, usuario, correo, contrasena, rol, creado_en)
  VALUES (?, ?, ?, ?, ?, ?, NOW())
");
$stmt->bind_param("sssssi", $nombre, $apellido, $usuario, $correo, $hash, $rol);

if ($stmt->execute()) {
  // Registrar historial
  $accion = "Creó usuario '$usuario'";
  $ip     = $_SERVER['REMOTE_ADDR'];
  $admin  = $_SESSION['usuario'];

  $stmt_hist = $conn->prepare("
    INSERT INTO historial_bd (usuario, accion, tabla_afectada, ip_usuario)
    VALUES (?, ?, 'usuarios', ?)
  ");
  if ($stmt_hist) {
    $stmt_hist->bind_param("sss", $admin, $accion, $ip);
    $stmt_hist->execute();
    $stmt_hist->close();
  }

  echo json_encode([
    'estado'  => 'ok',
    'mensaje' => '✅ Usuario creado correctamente'
  ]);
} else {
  echo json_encode([
    'estado'  => 'error',
    'mensaje' => '❌ Error al crear el usuario: ' . $conn->error
  ]);
}

$stmt->close();