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
    die("❌ Acceso denegado.");
}

// Verificar existencia de `historial_bd`
$sql_verificar = "SHOW TABLES LIKE 'historial_bd'";
$resultado_verificar = $conn->query($sql_verificar);

if (!$resultado_verificar || $resultado_verificar->num_rows == 0) {
    die("❌ Error: La tabla 'historial_bd' no existe.");
}

// Obtener filtros de búsqueda si se envían y escaparlos
$filtro_usuario = isset($_GET['usuario']) ? $conn->real_escape_string($_GET['usuario']) : '';
$filtro_tabla   = isset($_GET['tabla'])   ? $conn->real_escape_string($_GET['tabla'])   : '';
$filtro_fecha   = isset($_GET['fecha'])   ? $conn->real_escape_string($_GET['fecha'])   : '';
$filtro_accion  = isset($_GET['accion'])  ? $conn->real_escape_string($_GET['accion'])  : '';

// Construir condiciones dinámicas y almacenarlas en una variable

$condiciones = [];
if ($filtro_usuario) $condiciones[] = "usuario LIKE '%$filtro_usuario%'";
if ($filtro_tabla)   $condiciones[] = "tabla_afectada LIKE '%$filtro_tabla%'";
$where_sql = count($condiciones) ? ' WHERE ' . implode(' AND ', $condiciones) : '';


// $filtros = "";
// if ($filtro_usuario) $filtros .= " AND usuario LIKE '%$filtro_usuario%'";
// if ($filtro_tabla)   $filtros .= " AND tabla_afectada LIKE '%$filtro_tabla%'";
// if ($filtro_fecha)   $filtros .= " AND DATE(fecha) = '$filtro_fecha'";
// if ($filtro_accion)  $filtros .= " AND accion LIKE '%$filtro_accion%'";

// Consulta SQL con filtros dinámicos
$sql = "SELECT * FROM historial_bd WHERE 1=1 $filtros ORDER BY fecha DESC";

// Paginación
$limite  = 10;
$pagina  = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$inicio  = ($pagina - 1) * $limite;
$sql    .= " LIMIT $inicio, $limite";

$resultado = $conn->query($sql);

if (!$resultado) {
    die("❌ Error al obtener historial: " . $conn->error);
}

// Obtener total de registros para paginación (usando los mismos filtros)
$sql_total = "SELECT COUNT(*) as total FROM historial_bd WHERE 1=1 $filtros";
$resultado_total = $conn->query($sql_total);
$total_registros = $resultado_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $limite);

// Construir cadena de parámetros GET para la paginación
$params = "";
if ($filtro_usuario) $params .= "&usuario=" . urlencode($filtro_usuario);
if ($filtro_tabla)   $params .= "&tabla="   . urlencode($filtro_tabla);
if ($filtro_fecha)   $params .= "&fecha="   . urlencode($filtro_fecha);
if ($filtro_accion)  $params .= "&accion="  . urlencode($filtro_accion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Cambios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base.css">
</head>
<body>
<header>
    <h1>📜 Historial de Cambios en la Base de Datos</h1>
    <nav>
        <a href="admin_db.php" class="btn-nav">🏠 Panel Principal</a>
    </nav>
</header>

<main>
    <h3>Filtrar Historial</h3>
    <form method="GET" action="historial_bd.php">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" value="<?= htmlspecialchars($filtro_usuario) ?>">

        <label for="tabla">Tabla Afectada:</label>
        <input type="text" name="tabla" value="<?= htmlspecialchars($filtro_tabla) ?>">

        <label for="fecha">Fecha (YYYY-MM-DD):</label>
        <input type="date" name="fecha" value="<?= htmlspecialchars($filtro_fecha) ?>">

        <label for="accion">Acción:</label>
        <select name="accion">
            <option value="">Todas</option>
            <option value="Creó tabla" <?= $filtro_accion === "Creó tabla" ? "selected" : "" ?>>Solo Creaciones</option>
            <option value="Eliminó tabla" <?= $filtro_accion === "Eliminó tabla" ? "selected" : "" ?>>Solo Eliminaciones</option>
            <option value="Agregó columna" <?= $filtro_accion === "Agregó columna" ? "selected" : "" ?>>Solo Adiciones</option>
            <option value="Modificó columna" <?= $filtro_accion === "Modificó columna" ? "selected" : "" ?>>Solo Modificaciones</option>
        </select>

        <button type="submit">🔍 Filtrar</button>
    </form>

    <h3>Registros de Modificaciones</h3>
    <table border="1">
        <tr>
            <th>Usuario</th>
            <th>Acción</th>
            <th>Tabla</th>
            <th>Fecha</th>
            <th>IP</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($fila['usuario']) ?></td>
                <td><?= htmlspecialchars($fila['accion']) ?></td>
                <td><?= htmlspecialchars($fila['tabla_afectada']) ?></td>
                <td><?= htmlspecialchars($fila['fecha']) ?></td>
                <td><?= htmlspecialchars($fila['ip_usuario']) ?></td>
            </tr>
        <?php } ?>
    </table>

    <h3>Paginación</h3>
    <?php for ($i = 1; $i <= $total_paginas; $i++) { ?>
        <a href="historial_bd.php?pagina=<?= $i ?><?= $params ?>"><?= $i ?></a>
    <?php } ?>
</main>

<footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
</footer>
</body>
</html>
