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



require_once __DIR__ . '/../../includes/helpers.php';


// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Validar nombre de la tabla
if (!isset($_GET['tabla']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_GET['tabla'])) {
    die("❌ No se ha especificado una tabla válida.");
}
$tabla = htmlspecialchars($_GET['tabla']);

// Verificar si la tabla existe
$sql_verificar = "SHOW TABLES LIKE '$tabla'";
$resultado_verificar = $conn->query($sql_verificar);

if (!$resultado_verificar || $resultado_verificar->num_rows == 0) {
    die("❌ Error: La tabla '$tabla' no existe.");
}

// Obtener las columnas de la tabla con validación de errores
$sql = "SHOW COLUMNS FROM `$tabla`";
$resultado = $conn->query($sql);

if (!$resultado) {
    error_log("❌ Error en consulta SQL: " . $conn->error);
    die("❌ Error al obtener columnas: " . $conn->error);
}

// Registrar acceso en historial_bd
$usuario = $_SESSION['usuario'];
$accion = "Accedió a la edición de la tabla '$tabla'";
$ip_usuario = $_SERVER['REMOTE_ADDR'];
$sql_historial = "INSERT INTO historial_bd (usuario, accion, tabla_afectada, ip_usuario) VALUES ('$usuario', '$accion', '$tabla', '$ip_usuario')";
$conn->query($sql_historial);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tabla: <?= $tabla ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base.css">
</head>
<body>
<header>
    <h1>⚙️ Editar Tabla: <?= $tabla ?></h1>
    <nav class="nav-bar">
        <a href="admin_bd.php" class="btn-nav">🏠 Panel Principal</a>
        <a href="ver_datos.php?tabla=<?= $tabla ?>" class="btn-nav">🔍 Ver Datos</a>
    </nav>
</header>

<main>
    <h3>Estructura Actual</h3>
    <table border="1" id="tablaEstructura">
        <tr>
            <th>Columna</th>
            <th>Tipo de Dato</th>
            <th>Acción</th>
        </tr>
        <?php while ($columna = $resultado->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($columna['Field']) ?></td>
                <td><?= htmlspecialchars($columna['Type']) ?></td>
                <td>
                    <a href="modificar_columna.php?tabla=<?= $tabla ?>&columna=<?= htmlspecialchars($columna['Field']) ?>">🔄 Modificar</a>
                    <a href="eliminar_columna.php?tabla=<?= $tabla ?>&columna=<?= htmlspecialchars($columna['Field']) ?>" 
                       onclick="return confirm('⚠️ ¿Seguro que deseas eliminar esta columna?');">🗑 Eliminar</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <h3>Agregar Nueva Columna</h3>
    <form action="agregar_columna.php" method="POST">
        <input type="hidden" name="tabla" value="<?= $tabla ?>">
        
        <label for="nombre_columna">Nombre de la Columna:</label>
        <input type="text" name="nombre_columna" required>
        
        <label for="tipo_dato">Tipo de Dato:</label>
        <select name="tipo_dato" required>
            <option value="INT">INT (Entero)</option>
            <option value="VARCHAR(255)">VARCHAR (Texto)</option>
            <option value="DATE">DATE (Fecha)</option>
            <option value="TEXT">TEXT (Texto largo)</option>
            <option value="BOOLEAN">BOOLEAN (Verdadero/Falso)</option>
        </select>

        <label for="restriccion">Restricción:</label>
        <select name="restriccion">
            <option value="">Ninguna</option>
            <option value="NOT NULL">NOT NULL</option>
            <option value="UNIQUE">UNIQUE</option>
            <option value="DEFAULT 'valor'">DEFAULT 'valor'</option>
            <option value="CHECK (condicion)">CHECK (Condición)</option>
        </select>

        <button type="submit">➕ Agregar Columna</button>
    </form>
</main>

<footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
</footer>
</body>
</html>
