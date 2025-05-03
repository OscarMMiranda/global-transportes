<?php
session_start();
include '../includes/conexion.php';

// Solo admins pueden acceder
if (!isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: usuarios.php?error=id_invalido");
    exit();
}

$id = intval($_GET['id']);

// Prevenir que el admin actual se elimine a sí mismo
if ($_SESSION['id'] == $id) {
    header("Location: usuarios.php?error=admin_no_puede_eliminarse");
    exit();
}

// Verificar si el usuario existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    header("Location: usuarios.php?error=usuario_no_encontrado");
    exit();
}
$stmt->close();

// Eliminar usuario
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: usuarios.php?mensaje=usuario_eliminado");
} else {
    header("Location: usuarios.php?error=error_al_eliminar");
}
$stmt->close();
exit();
