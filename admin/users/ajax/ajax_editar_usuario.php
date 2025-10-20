<?php
// ajax_editar_usuario.php

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

// Respuesta JSON
header('Content-Type: application/json');

// Validar sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    echo json_encode(['estado' => 'error', 'mensaje' => 'Acceso no autorizado']);
    exit;
}

// Validar ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo json_encode(['estado' => 'error', 'mensaje' => 'ID no válido']);
    exit;
}

// Validar campos
$nombre   = isset($_POST['nombre'])   ? trim($_POST['nombre'])   : '';
$apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
$correo   = isset($_POST['correo'])   ? trim($_POST['correo'])   : '';
$rol      = isset($_POST['rol'])      ? intval($_POST['rol'])    : 0;

if ($nombre === '' || $apellido === '' || $correo === '' || $rol <= 0) {
    echo json_encode(['estado' => 'error', 'mensaje' => 'Campos incompletos']);
    exit;
}

// Actualizar usuario
$stmt = $conn->prepare("UPDATE usuarios SET nombre=?, apellido=?, correo=?, rol=? WHERE id=?");
$stmt->bind_param("ssssi", $nombre, $apellido, $correo, $rol, $id);

if ($stmt->execute()) {
    // Registrar historial con prepare
    $accion = "Modificó usuario ID $id vía AJAX";
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
        'mensaje' => '✅ Usuario actualizado correctamente',
        'datos'   => [
            'id'       => $id,
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'correo'   => $correo,
            'rol'      => $rol
        ]
    ]);
} else {
    echo json_encode([
        'estado'  => 'error',
        'mensaje' => '❌ Error al actualizar: ' . $conn->error
    ]);
}

$stmt->close();