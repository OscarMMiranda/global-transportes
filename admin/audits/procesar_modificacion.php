<?php
session_start();

// 2) Cargar conexión y helpers
require_once __DIR__ . '/../../includes/conexion.php';
require_once __DIR__ . '/../../includes/helpers.php';

// Activar modo depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado: Solo administradores pueden realizar cambios.");
}

// Validar los datos recibidos
if (!isset($_POST['tabla']) || !isset($_POST['columna']) || !isset($_POST['nuevo_tipo'])) {
    die("❌ Error: Todos los campos son obligatorios.");
}

$tabla = htmlspecialchars($_POST['tabla']);
$columna = htmlspecialchars($_POST['columna']);
$nuevo_tipo = htmlspecialchars($_POST['nuevo_tipo']);

// Ejecutar la modificación del tipo de datos
$sql = "ALTER TABLE `$tabla` MODIFY COLUMN `$columna` $nuevo_tipo";
$resultado = $conn->query($sql);

if (!$resultado) {
    error_log("❌ Error en la consulta SQL: " . $conn->error);
    die("❌ Error al modificar la columna: " . $conn->error);
}

// Registrar el cambio en `historial_bd`
$usuario = $_SESSION['usuario'];
$accion = "Modificó columna '$columna' en tabla '$tabla' a tipo '$nuevo_tipo'";
$sql_historial = "INSERT INTO historial_bd (usuario, accion, tabla_afectada) VALUES ('$usuario', '$accion', '$tabla')";
$conn->query($sql_historial);

// Redirigir de vuelta con mensaje de éxito
header("Location: editar_tabla.php?tabla=$tabla&mensaje=Columna modificada correctamente");
exit();
?>
