<?php
session_start();
require_once '../includes/conexion.php';

// Activar modo depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado: Solo administradores pueden eliminar tablas.");
}

// Validar el nombre de la tabla
if (!isset($_GET['tabla']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_GET['tabla'])) {
    die("❌ Error: Nombre de tabla no válido.");
}
$tabla = htmlspecialchars($_GET['tabla']);

// Verificar si la tabla realmente existe
$sql_verificar = "SHOW TABLES LIKE '$tabla'";
$resultado_verificar = $conn->query($sql_verificar);

if (!$resultado_verificar || $resultado_verificar->num_rows == 0) {
    die("❌ Error: La tabla '$tabla' no existe en la base de datos.");
}

// Confirmación interactiva antes de ejecutar la eliminación
if (!isset($_GET['confirmar'])) {
    echo "<script>
        if (confirm('⚠️ ¿Seguro que deseas eliminar la tabla \"$tabla\"? Esta acción es irreversible.')) {
            window.location.href = 'eliminar_tabla.php?tabla=$tabla&confirmar=1';
        } else {
            window.location.href = 'admin_db.php';
        }
    </script>";
    exit();
}

// Ejecutar la eliminación de la tabla con seguridad
$sql = "DROP TABLE `$tabla`";
if (!$conn->query($sql)) {
    error_log("❌ Error en la consulta SQL: " . $conn->error);
    die("❌ Error al eliminar la tabla: " . $conn->error);
}

// Registrar el cambio en `historial_bd`
$usuario = $_SESSION['usuario'];
$accion = "Eliminó la tabla '$tabla'";
$ip_usuario = $_SERVER['REMOTE_ADDR'];
$sql_historial = "INSERT INTO historial_bd (usuario, accion, tabla_afectada, ip_usuario) VALUES ('$usuario', '$accion', '$tabla', '$ip_usuario')";
$conn->query($sql_historial);

// Redirigir con mensaje de éxito
header("Location: admin_db.php?mensaje=✅ Tabla eliminada correctamente");
exit();
?>
