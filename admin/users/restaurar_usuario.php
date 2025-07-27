<?php
// /admin/users/restaurar_usuario.php

session_start();
require_once '/../../includes/conexion.php';

// 01. Activar modo depuración (quitar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// 02. Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    error_log("❌ Acceso no autorizado a restaurar usuario: " . $_SERVER['REMOTE_ADDR']);
    header("Location: ../login.php");
    exit();
}

// 03. Validar parámetro ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: usuarios_eliminados.php?error=id_invalido");
    exit();
}
$id = intval($_GET['id']);

// 04. Verificar que el usuario existe y está eliminado
$stmt = $conn->prepare("SELECT eliminado FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    header("Location: usuarios_eliminados.php?error=usuario_no_encontrado");
    exit();
}

$stmt->bind_result($eliminado);
$stmt->fetch();
$stmt->close();

if ($eliminado == 0) {
    header("Location: usuarios_eliminados.php?error=usuario_no_eliminado");
    exit();
}

// 05. Restaurar usuario (eliminado = 0, limpiar campos de eliminación)
$stmt = $conn->prepare("
    UPDATE usuarios 
    SET eliminado = 0, eliminado_por = NULL, eliminado_en = NULL 
    WHERE id = ?
");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // 06. Registrar acción en historial_bd
    $usuario_admin = $_SESSION['usuario'];
    $accion        = "[RESTAURACIÓN] Usuario ID $id restaurado";
    $ip_usuario    = $_SERVER['REMOTE_ADDR'];

    $hist = $conn->prepare("
        INSERT INTO historial_bd (usuario, accion, ip_usuario)
        VALUES (?, ?, ?)
    ");
    if ($hist) {
        $hist->bind_param("sss", $usuario_admin, $accion, $ip_usuario);
        $hist->execute();
        $hist->close();
    } else {
        error_log("❌ Error al preparar historial (restaurar): " . $conn->error);
    }

    header("Location: usuarios_eliminados.php?mensaje=usuario_restaurado");
} else {
    header("Location: usuarios_eliminados.php?error=error_al_restaurar");
}

$stmt->close();
exit();
?>
