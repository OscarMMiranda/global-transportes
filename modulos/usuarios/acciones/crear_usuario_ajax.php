<?php
// archivo: /modulos/usuarios/acciones/crear_usuario_ajax.php
// --------------------------------------------------------------
// Acción AJAX para crear un usuario (reemplazo de guardar.php)
// Compatible con hosting Ferozo / IIS
// --------------------------------------------------------------

// Log de errores
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_guardar.log');
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Respuesta JSON
header('Content-Type: application/json');

// --------------------------------------------------------------
// CARGA DE CONFIGURACIÓN
// --------------------------------------------------------------
$path_config = $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

if (!file_exists($path_config)) {
    error_log("crear_usuario_ajax.php: No se encontró config.php en $path_config");
    echo json_encode(["ok" => false, "errores" => ["Error interno de configuración."]]);
    exit;
}

require_once $path_config;

// --------------------------------------------------------------
// CARGA DE ARCHIVOS DEL SISTEMA
// --------------------------------------------------------------
if (!defined('INCLUDES_PATH')) {
    error_log("crear_usuario_ajax.php: INCLUDES_PATH no está definido en config.php");
    echo json_encode(["ok" => false, "errores" => ["Error interno de configuración."]]);
    exit;
}

require_once INCLUDES_PATH . '/permisos.php';
require_once INCLUDES_PATH . '/funciones.php';

$path_controller = __DIR__ . '/../controllers/usuarios_controller.php';

if (!file_exists($path_controller)) {
    error_log("crear_usuario_ajax.php: No existe usuarios_controller.php");
    echo json_encode(["ok" => false, "errores" => ["Error interno del módulo usuarios."]]);
    exit;
}

require_once $path_controller;

// --------------------------------------------------------------
// SESIÓN Y CONEXIÓN
// --------------------------------------------------------------
session_start();

$conn = getConnection();

if (!$conn) {
    error_log("crear_usuario_ajax.php: No se pudo conectar a la base de datos");
    echo json_encode(["ok" => false, "errores" => ["Error de conexión a la base de datos."]]);
    exit;
}

// --------------------------------------------------------------
// VALIDAR PERMISOS
// --------------------------------------------------------------
try {
    requirePermiso('usuarios', 'crear');
} catch (Exception $e) {
    error_log("crear_usuario_ajax.php: requirePermiso lanzó excepción: " . $e->getMessage());
    echo json_encode(["ok" => false, "errores" => [$e->getMessage()]]);
    exit;
}

// --------------------------------------------------------------
// CAPTURA DE DATOS
// --------------------------------------------------------------
$nombre   = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$usuario  = trim($_POST['usuario'] ?? '');
$correo   = trim($_POST['correo'] ?? '');
$rol      = trim($_POST['rol'] ?? '');
$password = trim($_POST['password'] ?? '');

// --------------------------------------------------------------
// VALIDACIÓN
// --------------------------------------------------------------
$errores = [];

if ($nombre === '')   $errores[] = "El nombre es obligatorio.";
if ($apellido === '') $errores[] = "El apellido es obligatorio.";
if ($usuario === '')  $errores[] = "El usuario es obligatorio.";
if ($correo === '')   $errores[] = "El correo es obligatorio.";
if ($password === '') $errores[] = "La contraseña es obligatoria.";

if (!empty($errores)) {
    echo json_encode(["ok" => false, "errores" => $errores]);
    exit;
}

// --------------------------------------------------------------
// CREAR USUARIO
// --------------------------------------------------------------
$creado = crearUsuario($conn, $nombre, $apellido, $usuario, $correo, $rol, $password);

if (!$creado) {
    error_log("crear_usuario_ajax.php: crearUsuario() devolvió false");
    echo json_encode(["ok" => false, "errores" => ["No se pudo crear el usuario."]]);
    exit;
}

echo json_encode(["ok" => true, "mensaje" => "Usuario creado correctamente."]);
exit;