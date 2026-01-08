<?php
	// archivo: /includes/permisos.php
	// --------------------------------------------------------------
	// Gestión de permisos de usuarios  
	// --------------------------------------------------------------
	// Iniciar sesión si no está iniciada
	// --------------------------------------------------------------

	if (session_status() === PHP_SESSION_NONE) {
		session_start();
		}

	require_once __DIR__ . '/conexion.php';
	require_once __DIR__ . '/funciones.php'; // para registrarEnHistorial si no estaba incluido

	// Cache interno para no consultar la BD en cada validación.
	 
	$__cache_permisos_rol = [];
	$__cache_permisos_usuario = [];

	/**
	 * Obtener permisos asignados a un rol.
	 */
function getPermisosRol($rol_id) {
    global $__cache_permisos_rol;

    if (isset($__cache_permisos_rol[$rol_id])) {
        return $__cache_permisos_rol[$rol_id];
    }

    $conn = getConnection();
    if (!$conn) {
        return [];
    }

    $sql = "
        SELECT p.modulo, p.accion
        FROM roles_permisos rp
        JOIN permisos p ON p.id = rp.permiso_id
        WHERE rp.rol_id = ?
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [];
    }

    $stmt->bind_param("i", $rol_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $permisos = [];
    while ($row = $result->fetch_assoc()) {
        $permisos[$row['modulo']][$row['accion']] = true;
    }

    $stmt->close();

    $__cache_permisos_rol[$rol_id] = $permisos;
    return $permisos;
}

/**
 * Obtener permisos especiales asignados a un usuario.
 */
function getPermisosUsuario($usuario_id) {
    global $__cache_permisos_usuario;

    if (isset($__cache_permisos_usuario[$usuario_id])) {
        return $__cache_permisos_usuario[$usuario_id];
    }

    $conn = getConnection();
    if (!$conn) {
        return [];
    }

    $sql = "
        SELECT p.modulo, p.accion
        FROM usuarios_permisos up
        JOIN permisos p ON p.id = up.permiso_id
        WHERE up.usuario_id = ?
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [];
    }

    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $permisos = [];
    while ($row = $result->fetch_assoc()) {
        $permisos[$row['modulo']][$row['accion']] = true;
    }

    $stmt->close();

    $__cache_permisos_usuario[$usuario_id] = $permisos;
    return $permisos;
}

/**
 * Verificar si un usuario tiene un permiso específico.
 */
function tienePermiso($usuario_id, $modulo, $accion) {
    $conn = getConnection();
    if (!$conn) {
        return false;
    }

    // 1) Obtener rol del usuario
    $sql = "SELECT rol FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->bind_result($rol_id);
    $stmt->fetch();
    $stmt->close();

    if (!$rol_id) {
        return false;
    }

    // 2) Permisos por rol
    $permisosRol = getPermisosRol($rol_id);
    if (isset($permisosRol[$modulo][$accion])) {
        return true;
    }

    // 3) Permisos especiales por usuario
    $permisosUsuario = getPermisosUsuario($usuario_id);
    if (isset($permisosUsuario[$modulo][$accion])) {
        return true;
    }

    return false;
}

/**
 * Bloquear acceso si el usuario no tiene permiso.
 * Versión profesional:
 * - No rompe la página con HTML crudo.
 * - Registra intento fallido.
 * - Redirige al módulo con mensaje en $_SESSION['error'].
 */
function requirePermiso($modulo, $accion) {
    // 1. Validar sesión
    if (!isset($_SESSION['usuario_id'])) {
        $_SESSION['error'] = "Debes iniciar sesión para continuar.";
        header("Location: /login.php");
        exit;
    }

    // 2. Validar permiso
    if (!tienePermiso($_SESSION['usuario_id'], $modulo, $accion)) {

        // 2.1 Registrar intento fallido en historial (si hay conexión)
        $conn = getConnection();
        if ($conn) {
            $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'desconocido';
            $ip      = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'IP desconocida';

            // Si tienes esta función en funciones.php
            if (function_exists('registrarEnHistorial')) {
                registrarEnHistorial(
                    $conn,
                    $usuario,
                    "Intento NO AUTORIZADO de acceder a $modulo → $accion",
                    $modulo,
                    $ip
                );
            }
        }

        // 2.2 Mensaje elegante para el usuario
        $_SESSION['error'] = "No tienes permiso para realizar esta acción.";

        // 2.3 Redirigir al módulo correspondiente
        header("Location: /modulos/$modulo/index.php");
        exit;
    }
}