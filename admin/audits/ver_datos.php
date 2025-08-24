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
    die("❌ Acceso denegado: Solo administradores pueden ver los datos.");
}

// Validar si se recibió el nombre de la tabla
if (!isset($_GET['tabla']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_GET['tabla'])) {
    die("❌ Error: Tabla no válida.");
}
$tabla = htmlspecialchars($_GET['tabla']);

// Verificar si la tabla existe antes de consultarla
$sql_verificar = "SHOW TABLES LIKE '$tabla'";
$resultado_verificar = $conn->query($sql_verificar);

if (!$resultado_verificar || $resultado_verificar->num_rows == 0) {
    die("❌ Error: La tabla '$tabla' no existe en la base de datos.");
}

// Obtener columnas de la tabla
$sql_columnas = "SHOW COLUMNS FROM `$tabla`";
$resultado_columnas = $conn->query($sql_columnas);

if (!$resultado_columnas || $resultado_columnas->num_rows == 0) {
    die("❌ Error: No se pudieron obtener columnas de la tabla.");
}

// Generar lista de columnas de manera segura
$columnas = [];
while ($columna = $resultado_columnas->fetch_assoc()) {
    $columnas[] = "`" . $columna['Field'] . "`";
}

// Filtros dinámicos para búsqueda
$filtro = "";
$buscar = isset($_GET['buscar']) ? $conn->real_escape_string($_GET['buscar']) : '';

if (!empty($buscar)) {
    $filtro = "WHERE CONCAT_WS(' ', " . implode(", ", $columnas) . ") LIKE '%$buscar%'";
}

// Paginación segura
$limite = 10; // Registros por página
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $limite;

// Obtener registros de la tabla con paginación
$sql_datos = "SELECT * FROM `$tabla` $filtro ORDER BY 1 DESC LIMIT $inicio, $limite";
$resultado_datos = $conn->query($sql_datos);

if (!$resultado_datos) {
    die("❌ Error al obtener los datos: " . $conn->error);
}

// Obtener total de registros para paginación
$sql_total = "SELECT COUNT(*) as total FROM `$tabla` $filtro";
$resultado_total = $conn->query($sql_total);
$total_registros = $resultado_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $limite);

// Exportación a CSV sin errores
if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="datos_'.$tabla.'.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array_column($resultado_columnas->fetch_all(MYSQLI_ASSOC), 'Field'));

    while ($fila = $resultado_datos->fetch_assoc()) {
        fputcsv($output, $fila);
    }
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Datos - <?= $tabla ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base.css">
</head>
<body>
<header>
    <h1>📊 Datos en <?= $tabla ?></h1>
    <nav class="nav-bar">
        <a href="admin_bd.php" class="btn-nav">🏠 Panel Principal</a>
        <a href="editar_tabla.php?tabla=<?= $tabla ?>" class="btn-nav">⚙️ Editar Tabla</a>
        <a href="ver_datos.php?tabla=<?= $tabla ?>&exportar=csv" class="btn-nav">📥 Exportar a CSV</a>
    </nav>
</header>

<main>
    <h3>Filtrar Datos</h3>
    <form method="GET" action="ver_datos.php">
        <input type="hidden" name="tabla" value="<?= $tabla ?>">
        <label for="buscar">Buscar:</label>
        <input type="text" name="buscar" value="<?= $buscar ?>">
        <button type="submit">🔍 Buscar</button>
    </form>

    <h3>Registros en la Tabla</h3>
    <table border="1">
        <tr>
            <?php foreach ($resultado_columnas->fetch_all(MYSQLI_ASSOC) as $columna) { ?>
                <th><?= htmlspecialchars($columna['Field']) ?></th>
            <?php } ?>
        </tr>
        <?php while ($fila = $resultado_datos->fetch_assoc()) { ?>
            <tr>
                <?php foreach ($fila as $valor) { ?>
                    <td><?= htmlspecialchars($valor) ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>

    <h3>Paginación</h3>
    <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
        <a href="ver_datos.php?tabla=<?= $tabla ?>&pagina=<?= $i ?>"><?= $i ?></a>
    <?php } ?>
</main>

<footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
</footer>
</body>
</html>
