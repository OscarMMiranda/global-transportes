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
    die("❌ Acceso denegado.");
}

// Validar datos recibidos
if (!isset($_POST['nombre_tabla']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['nombre_tabla'])) {
    die("<h3>⚠️ Error: Nombre de tabla no válido.</h3>
         <a href='crear_tabla.php'>🔄 Volver a Crear Tabla</a>");
}

$tabla = htmlspecialchars($_POST['nombre_tabla']);

// Verificar si la tabla ya existe
$sql_verificar = "SHOW TABLES LIKE '$tabla'";
$resultado_verificar = $conn->query($sql_verificar);

if ($resultado_verificar->num_rows > 0) {
    die("<h3>⚠️ La tabla '$tabla' ya existe.</h3>
         <a href='crear_tabla.php'>🔄 Volver a Crear Tabla</a>");
}

// Si no se definieron columnas, ofrecer opciones
if (empty($_POST['columnas'])) {
    echo "<h3>⚠️ No se puede crear una tabla sin columnas definidas.</h3>";
    echo "<p>Para continuar, puedes:</p>";
    echo "<ul>
            <li><a href='crear_tabla.php'>🔄 Volver y agregar columnas manualmente.</a></li>
            <li><a href='procesar_creacion.php?tabla=$tabla&estructura_basica=1'>⚙️ Crear con estructura básica.</a></li>
          </ul>";
    exit();
}

// Si el usuario selecciona estructura básica
if (isset($_GET['estructura_basica'])) {
    $_POST['columnas'] = ['id', 'fecha_creacion', 'estado'];
    $_POST['tipos'] = ['INT AUTO_INCREMENT PRIMARY KEY', 'DATE', 'BOOLEAN'];
    $_POST['restricciones'] = ['', '', 'DEFAULT TRUE'];
}

// Construcción segura de la consulta SQL
$columnas_sql = [];
foreach ($_POST['columnas'] as $index => $columna) {
    $columna_limpia = htmlspecialchars($columna);
    $tipo = htmlspecialchars($_POST['tipos'][$index]);
    $restriccion = !empty($_POST['restricciones'][$index]) ? htmlspecialchars($_POST['restricciones'][$index]) : "";
    $columnas_sql[] = "`$columna_limpia` $tipo $restriccion";
}

$sql = "CREATE TABLE `$tabla` (" . implode(", ", $columnas_sql) . ")";
if (!$conn->query($sql)) {
    error_log("❌ Error al crear tabla: " . $conn->error);
    die("❌ Error al crear la tabla: " . $conn->error);
}

// **Registrar el cambio en `historial_bd`**
$usuario = $_SESSION['usuario'];
$accion = "Creó la tabla '$tabla'";
$ip_usuario = $_SERVER['REMOTE_ADDR'];
$sql_historial = "INSERT INTO historial_bd (usuario, accion, tabla_afectada, ip_usuario) VALUES ('$usuario', '$accion', '$tabla', '$ip_usuario')";
$conn->query($sql_historial);

// Redirigir con mensaje de éxito
header("Location: admin_db.php?mensaje=✅ Tabla '$tabla' creada y registrada en historial correctamente");
exit();
?>
