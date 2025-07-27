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

// Validar si la tabla y columna fueron enviadas
if (!isset($_GET['tabla']) || !isset($_GET['columna'])) {
    die("❌ Error: No se ha especificado una tabla y columna.");
}

$tabla = htmlspecialchars($_GET['tabla']);
$columna = htmlspecialchars($_GET['columna']);

// Obtener el tipo de dato actual
$sql = "SHOW COLUMNS FROM `$tabla` LIKE '$columna'";
$resultado = $conn->query($sql);

if (!$resultado || $resultado->num_rows === 0) {
    die("❌ Error: No se encontró la columna especificada.");
}

$datos_columna = $resultado->fetch_assoc();
$tipo_actual = $datos_columna['Type'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Columna: <?= $columna ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base.css">
</head>
<body>
    <header>
        <h1>⚙️ Modificar Columna: <?= $columna ?> en <?= $tabla ?></h1>
    </header>

    <main>
        <h3>Tipo actual: <?= $tipo_actual ?></h3>
        <form action="procesar_modificacion.php" method="POST">
            <input type="hidden" name="tabla" value="<?= $tabla ?>">
            <input type="hidden" name="columna" value="<?= $columna ?>">

            <label for="nuevo_tipo">Nuevo Tipo de Dato:</label>
            <select name="nuevo_tipo" required>
                <option value="INT">INT (Entero)</option>
                <option value="VARCHAR(255)">VARCHAR (Texto corto)</option>
                <option value="TEXT">TEXT (Texto largo)</option>
                <option value="DATE">DATE (Fecha)</option>
                <option value="BOOLEAN">BOOLEAN (Verdadero/Falso)</option>
            </select>

            <button type="submit">🔄 Modificar Tipo de Dato</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
