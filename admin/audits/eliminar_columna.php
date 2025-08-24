<?php
session_start();

// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../includes/config.php';

$conn = getConnection();

// 2) Cargar conexión y helpers

require_once __DIR__ . '/../../includes/helpers.php';


// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado: Solo administradores pueden realizar cambios.");
}

// Validar los parámetros enviados
if (!isset($_GET['tabla']) || !isset($_GET['columna'])) {
    die("❌ Error: No se ha especificado una tabla y columna.");
}

$tabla = htmlspecialchars($_GET['tabla']);
$columna = htmlspecialchars($_GET['columna']);

// Verificar si la columna realmente existe en la tabla
$sql_verificar = "SHOW COLUMNS FROM `$tabla` LIKE '$columna'";
$resultado_verificar = $conn->query($sql_verificar);

if (!$resultado_verificar || $resultado_verificar->num_rows == 0) {
    die("❌ Error: La columna '$columna' no existe en la tabla '$tabla'.");
}

// Confirmación interactiva antes de ejecutar la eliminación
if (!isset($_GET['confirmar'])) {
    echo "<script>
        if (confirm('⚠️ ¿Seguro que deseas eliminar la columna \"$columna\" de la tabla \"$tabla\"? Esta acción no se puede deshacer.')) {
            window.location.href = 'eliminar_columna.php?tabla=$tabla&columna=$columna&confirmar=1';
        } else {
            window.location.href = 'editar_tabla.php?tabla=$tabla';
        }
    </script>";
    exit();
}

// Ejecutar la eliminación de la columna
$sql = "ALTER TABLE `$tabla` DROP COLUMN `$columna`";
if (!$conn->query($sql)) {
    error_log("❌ Error en la consulta SQL: " . $conn->error);
    die("❌ Error al eliminar columna: " . $conn->error);
}

// Registrar el cambio en `historial_bd` con IP del usuario
$usuario = $_SESSION['usuario'];
$accion = "Eliminó columna '$columna' de la tabla '$tabla'";
$ip_usuario = $_SERVER['REMOTE_ADDR'];
$sql_historial = "INSERT INTO historial_bd (usuario, accion, tabla_afectada, ip_usuario) VALUES ('$usuario', '$accion', '$tabla', '$ip_usuario')";
$conn->query($sql_historial);

// Redirigir con mensaje de éxito
header("Location: editar_tabla.php?tabla=$tabla&mensaje=✅ Columna eliminada correctamente");
exit();
?>
