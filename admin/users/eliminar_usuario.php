<?php
session_start();
include '/../../includes/conexion.php';

// Solo admins pueden acceder
if (!isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: users.php?error=id_invalido");
    exit();
}

$id = intval($_GET['id']);
$id_admin = intval($_SESSION['id']);

// Prevenir que el admin actual se elimine a sí mismo
if ($_SESSION['id'] == $id) {
    header("Location: users.php?error=admin_no_puede_eliminarse");
    exit();
}

// Verificar si el usuario existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    header("Location: users.php?error=usuario_no_encontrado");
    exit();
}
$stmt->close();

// Eliminar usuario
// $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
// $stmt->bind_param("i", $id);

// Eliminación lógica
$stmt = $conn->prepare("UPDATE usuarios SET eliminado = 1, eliminado_por = ?, eliminado_en = NOW() WHERE id = ?");
$stmt->bind_param("ii", $id_admin, $id);

if ($stmt->execute()) {
    // Registrar en historial
    $usuario_admin = $_SESSION['usuario'];
    $accion = "[ELIMINACIÓN] Usuario ID $id marcado como eliminado";
    $ip = $_SERVER['REMOTE_ADDR'];

    $historial = $conn->prepare("INSERT INTO historial_bd (usuario, accion, ip_usuario) VALUES (?, ?, ?)");
    $historial->bind_param("sss", $usuario_admin, $accion, $ip);
    $historial->execute();
    $historial->close();

    header("Location: users.php?mensaje=usuario_eliminado");
} else {
    header("Location: users.php?error=error_al_eliminar");
}
$stmt->close();
exit();
