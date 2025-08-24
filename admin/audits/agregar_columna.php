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

// Validar los datos recibidos
if (!isset($_POST['tabla']) || !isset($_POST['nombre_columna']) || !isset($_POST['tipo_dato'])) {
    die("❌ Error: Todos los campos son obligatorios.");
}

$tabla = htmlspecialchars($_POST['tabla']);
$nombre_columna = htmlspecialchars($_POST['nombre_columna']);
$tipo_dato = htmlspecialchars($_POST['tipo_dato']);
$restriccion = isset($_POST['restriccion']) ? htmlspecialchars($_POST['restriccion']) : '';

// Validar el nombre de la columna (evitar caracteres peligrosos)
if (!preg_match('/^[a-zA-Z0-9_]+$/', $nombre_columna)) {
    die("❌ Error: El nombre de la columna no es válido.");
}

// Construir la consulta SQL
$sql = "ALTER TABLE `$tabla` ADD `$nombre_columna` $tipo_dato $restriccion";
$resultado = $conn->query($sql);

if (!$resultado) {
    error_log("❌ Error en la consulta SQL: " . $conn->error);
    die("❌ Error en la consulta: " . $conn->error);
}

// Registrar el cambio en `historial_bd`
$usuario = $_SESSION['usuario'];
$accion = "Agregó columna '$nombre_columna' en tabla '$tabla' con tipo '$tipo_dato'";
$sql_historial = "INSERT INTO historial_bd (usuario, accion, tabla_afectada) VALUES ('$usuario', '$accion', '$tabla')";
$conn->query($sql_historial);

// Redirigir de vuelta con mensaje de éxito
header("Location: editar_tabla.php?tabla=$tabla&mensaje=Columna agregada correctamente");
exit();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Columna - <?= $tabla ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base.css">
</head>
<body>
    <header>
        <h1>➕ Agregar Columna en <?= $tabla ?></h1>
    </header>

    <main>
        <form action="agregar_columna.php" method="POST">
            <input type="hidden" name="tabla" value="<?= $tabla ?>">
            
            <label for="nombre_columna">Nombre de la Columna:</label>
            <input type="text" name="nombre_columna" required>

            <label for="tipo_dato">Tipo de Dato:</label>
            <select name="tipo_dato" required>
                <option value="INT">INT (Entero)</option>
                <option value="VARCHAR(255)">VARCHAR (Texto corto)</option>
                <option value="TEXT">TEXT (Texto largo)</option>
                <option value="DATE">DATE (Fecha)</option>
                <option value="BOOLEAN">BOOLEAN (Verdadero/Falso)</option>
            </select>

            <label for="restriccion">Restricción:</label>
            <select name="restriccion">
                <option value="">Ninguna</option>
                <option value="NOT NULL">NOT NULL</option>
                <option value="UNIQUE">UNIQUE</option>
                <option value="DEFAULT 'valor'">DEFAULT 'valor'</option>
                <option value="CHECK (condicion)">CHECK (condición)</option>
            </select>

            <button type="submit">➕ Agregar Columna</button>
        </form>

        <div class="nav-buttons">
            <a href="editar_tabla.php?tabla=<?= $tabla ?>" class="btn-nav">⬅️ Volver a la Tabla</a>
            <a href="admin_db.php" class="btn-nav">🏠 Panel Principal</a>
            <button type="reset" class="btn-cancel">❌ Cancelar</button>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
