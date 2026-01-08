<?php
	// archivo: modulos/usuarios/acciones/desactivar.php
// --------------------------------------------------------------
// Desactivar un usuario
// --------------------------------------------------------------
// Configurar reporte de errores para desarrollo		


ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/permisos.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/modulos/usuarios/controllers/usuarios_controller.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = getConnection();
if (!$conn) {
    error_log("❌ Error: no hay conexión en desactivar.php");
    header("Location: ../index.php?error=conexion");
    exit;
}

// Validar permiso profesionalmente
requirePermiso('usuarios', 'desactivar');

// Validar ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    $_SESSION['error'] = "ID de usuario inválido.";
    header("Location: ../index.php");
    exit;
}

// Ejecutar desactivación
$ok = cambiarEstadoUsuario($conn, $id, 1);

// Registrar en historial
$usuarioActual = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'desconocido';

registrarEnHistorial(
    $conn,
    $usuarioActual,
    $ok ? "Desactivó al usuario ID $id" : "Intento fallido de desactivar ID $id",
    "usuarios",
    $_SERVER['REMOTE_ADDR']
);

// Redirigir con mensaje
if ($ok) {
    $_SESSION['success'] = "Usuario desactivado correctamente.";
} else {
    $_SESSION['error'] = "No se pudo desactivar el usuario.";
}

header("Location: ../index.php");
exit;