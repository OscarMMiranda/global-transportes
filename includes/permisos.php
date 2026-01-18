<?php
// archivo: /includes/permisos.php
// --------------------------------------------------------------
// Sistema moderno de permisos compatible con PHP 5.6
// --------------------------------------------------------------

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar conexión y funciones globales
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/funciones.php';

// Cache interno para evitar consultas repetidas
$__cache_permisos_rol = array();

/**
 * Obtener permisos asignados a un rol (cacheado)
 */
function getPermisosRol($rol_id)
{
    global $__cache_permisos_rol;

    if (isset($__cache_permisos_rol[$rol_id])) {
        return $__cache_permisos_rol[$rol_id];
    }

    $conn = getConnection();
    if (!$conn) return array();

    $sql = "
        SELECT m.nombre AS modulo, a.nombre AS accion
        FROM permisos_roles pr
        JOIN modulos m ON m.id = pr.modulo_id
        JOIN acciones a ON a.id = pr.accion_id
        WHERE pr.rol_id = ?
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) return array();

    $stmt->bind_param("i", $rol_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $permisos = array();
    while ($row = $result->fetch_assoc()) {
        if (!isset($permisos[$row['modulo']])) {
            $permisos[$row['modulo']] = array();
        }
        $permisos[$row['modulo']][$row['accion']] = true;
    }

    $__cache_permisos_rol[$rol_id] = $permisos;
    return $permisos;
}

/**
 * Verificar si un usuario tiene un permiso específico
 */
function tienePermiso($usuario_id, $modulo, $accion)
{
    $conn = getConnection();
    if (!$conn) return false;

    // Obtener rol del usuario
    $sql = "SELECT rol FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->bind_result($rol_id);
    $stmt->fetch();
    $stmt->close();

    if (!$rol_id) return false;

    // Permisos por rol
    $permisosRol = getPermisosRol($rol_id);

    return isset($permisosRol[$modulo]) && isset($permisosRol[$modulo][$accion]);
}

/**
 * Bloquear acceso si el usuario no tiene permiso
 */
function requirePermiso($modulo, $accion)
{
    // Validar sesión
    if (!isset($_SESSION['usuario_id'])) {

        // Si es AJAX → devolver JSON
        if (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            echo json_encode(array(
                "ok" => false,
                "msg" => "Debes iniciar sesión para continuar."
            ));
            exit;
        }

        // Navegación normal → redirigir al login
        header("Location: /login.php");
        exit;
    }

    // Validar permiso
    if (!tienePermiso($_SESSION['usuario_id'], $modulo, $accion)) {

        // Registrar intento fallido
        $conn = getConnection();
        if ($conn && function_exists('registrarEnHistorial')) {

            $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'desconocido';
            $ip      = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'IP desconocida';

            registrarEnHistorial(
                $conn,
                $usuario,
                "Intento NO AUTORIZADO de acceder a $modulo → $accion",
                $modulo,
                $ip
            );
        }

        // Si es AJAX → devolver JSON limpio
        if (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            echo json_encode(array(
                "ok" => false,
                "msg" => "No tienes permiso para realizar esta acción."
            ));
            exit;
        }

        // Navegación normal → regresar a la página anterior
        $volver = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
        header("Location: " . $volver);
        exit;
    }
}