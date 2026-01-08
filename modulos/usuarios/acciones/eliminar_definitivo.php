<?php
// archivo: /modulos/usuarios/acciones/eliminar.php
// ------------------------------------------------------
// Elimina un usuario de forma definitiva (hard delete)
// ------------------------------------------------------

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/auditoria.php';

require_once __DIR__ . '/../controllers/usuarios_controller.php';

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido.");
}

$id = intval($_GET['id']);

// Conexión
$conn = getConnection();
if (!$conn) {
    die("Error de conexión a la base de datos.");
}

try {

    // Llamar al controlador para eliminar definitivamente
    $ok = eliminarUsuarioDefinitivo($conn, $id);

    if ($ok) {
        // Redirigir de vuelta a la lista
        header("Location: /modulos/usuarios/index.php?msg=eliminado");
        exit;
    } else {
        die("No se pudo eliminar el usuario.");
    }

} catch (Exception $e) {

    die("Error interno: " . $e->getMessage());
}