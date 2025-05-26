<?php
session_start();
require_once '../includes/conexion.php';

// Activar depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    die("❌ Acceso denegado.");
}

// Validar si se recibió el nombre de la tabla
if (!isset($_GET['tabla']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_GET['tabla'])) {
    die("❌ Error: Tabla no válida.");
}
$tabla = htmlspecialchars($_GET['tabla']);

// Verificar si la tabla existe
$sql_verificar = "SHOW TABLES LIKE '$tabla'";
$resultado_verificar = $conn->query($sql_verificar);

if (!$resultado_verificar || $resultado_verificar->num_rows == 0) {
    die("❌ Error: La tabla '$tabla' no existe en la base de datos.");
}

// Paginación segura
$limite = 10;
$pagina = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $limite;

// Obtener registros con paginación
$sql = "SELECT * FROM `$tabla` ORDER BY 1 DESC LIMIT $inicio, $limite";
$resultado = $conn->query($sql);

if (!$resultado) {
    die("❌ Error en consulta SQL: " . $conn->error);
}

// Obtener total de registros para paginación
$sql_total = "SELECT COUNT(*) as total FROM `$tabla`";
$resultado_total = $conn->query($sql_total);
$total_registros = $resultado_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $limite);

// Exportación a CSV
if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="datos_'.$tabla.'.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array_column($resultado->fetch_fields(), 'name'));

    while ($fila = $resultado->fetch_assoc()) {
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
    <title>Editar Registros - <?= $tabla ?></title>
    <link rel="stylesheet" href="../css/base.css">
    <script>
        function confirmarEliminacion(id) {
            return confirm("⚠️ ¿Seguro que deseas eliminar el registro ID " + id + "? Esta acción no se puede deshacer.");
        }
    </script>
</head>
<body>
<header>
    <h1>🖊️ Editar Registros en <?= $tabla ?></h1>
    <nav>
        <a href="admin_db.php" class="btn-nav">🏠 Panel Principal</a>
        <a href="ver_datos.php?tabla=<?= $tabla ?>" class="btn-nav">🔍 Ver Datos</a>
        <a href="revisar_registros.php?tabla=<?= $tabla ?>&exportar=csv" class="btn-nav">📥 Exportar a CSV</a>
    </nav>
</header>

<main>
    <h3>Registros de la Tabla</h3>
    <form action="procesar_edicion.php" method="POST">
        <input type="hidden" name="tabla" value="<?= $tabla ?>">
        <table border="1">
            <tr>
                <?php foreach ($resultado->fetch_fields() as $campo) { ?>
                    <th><?= htmlspecialchars($campo->name) ?></th>
                <?php } ?>
                <th>Acción</th>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()) { ?>
                <tr>
                    <?php foreach ($fila as $campo => $valor) { ?>
                        <td><input type="text" name="datos[<?= $fila['id'] ?>][<?= $campo ?>]" value="<?= htmlspecialchars($valor) ?>"></td>
                    <?php } ?>
                    <td>
                        <button type="submit" name="guardar" value="<?= $fila['id'] ?>">💾 Guardar</button>
                        <a href="eliminar_registro.php?tabla=<?= $tabla ?>&id=<?= $fila['id'] ?>" 
                           onclick="return confirmarEliminacion(<?= $fila['id'] ?>)">🗑 Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </form>

    <h3>Paginación</h3>
    <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
        <a href="revisar_registros.php?tabla=<?= $tabla ?>&pagina=<?= $i ?>"><?= $i ?></a>
    <?php } ?>
</main>

<footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
</footer>
</body>
</html>
