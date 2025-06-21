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

// Verificar existencia de tabla
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


// Sanitizar entrada (recomendación: reemplazar por prepared statements en etapa avanzada)
$filtro_usuario = $conn->real_escape_string($filtro_usuario);
$filtro_tabla   = $conn->real_escape_string($filtro_tabla);
$filtro_fecha   = $conn->real_escape_string($filtro_fecha);
$filtro_accion  = $conn->real_escape_string($filtro_accion);

// Armado de filtros dinámicos
$condiciones = [];
if ($filtro_usuario) $condiciones[] = "usuario LIKE '%$filtro_usuario%'";
if ($filtro_tabla)   $condiciones[] = "tabla_afectada LIKE '%$filtro_tabla%'";
if ($filtro_fecha)   $condiciones[] = "DATE(fecha) = '$filtro_fecha'";
if ($filtro_accion)  $condiciones[] = "accion LIKE '%$filtro_accion%'";

$where = count($condiciones) ? 'WHERE ' . implode(' AND ', $condiciones) : '';

// Paginación
$limite  = 10;
$pagina  = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$inicio  = ($pagina - 1) * $limite;

// Consulta principal
$sql = "SELECT * FROM historial_bd $where ORDER BY fecha DESC LIMIT $inicio, $limite";
$resultado = $conn->query($sql);
if (!$resultado) {
    die("❌ Error al obtener historial: " . $conn->error);
}

// Total registros para paginación
$sql_total = "SELECT COUNT(*) as total FROM historial_bd $where";
$total = $conn->query($sql_total)->fetch_assoc();
$total_paginas = ceil($total['total'] / $limite);

// Param string para enlaces
$params = "";
foreach (['usuario', 'tabla', 'fecha', 'accion'] as $campo) {
    if (!empty($_GET[$campo])) {
        $params .= "&$campo=" . urlencode($_GET[$campo]);
    }
}
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
    <section>
        <h3>🔎 Filtros</h3>
        <form method="GET" action="historial_bd.php">
            <label>Usuario:</label>
            <input type="text" name="usuario" value="<?= htmlspecialchars($filtro_usuario) ?>">

            <label>Tabla:</label>
            <input type="text" name="tabla" value="<?= htmlspecialchars($filtro_tabla) ?>">

            <label>Fecha:</label>
            <input type="date" name="fecha" value="<?= htmlspecialchars($filtro_fecha) ?>">

            <label>Acción:</label>
            <select name="accion">
                <option value="">Todas</option>
                <option value="Creó tabla" <?= $filtro_accion === "Creó tabla" ? "selected" : "" ?>>Creaciones</option>
                <option value="Eliminó tabla" <?= $filtro_accion === "Eliminó tabla" ? "selected" : "" ?>>Eliminaciones</option>
                <option value="Agregó columna" <?= $filtro_accion === "Agregó columna" ? "selected" : "" ?>>Adiciones</option>
                <option value="Modificó columna" <?= $filtro_accion === "Modificó columna" ? "selected" : "" ?>>Modificaciones</option>
            </select>
            <button type="submit">Filtrar</button>
            <a href="historial_bd.php">⟳ Limpiar</a>
        </form>
    </section>

    <section>
        <h3>📂 Registros</h3>
        <?php if ($resultado->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Tabla</th>
                    <th>Fecha</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['usuario']) ?></td>
                    <td><?= htmlspecialchars($fila['accion']) ?></td>
                    <td><?= htmlspecialchars($fila['tabla_afectada']) ?></td>
                    <td><?= htmlspecialchars($fila['fecha']) ?></td>
                    <td><?= htmlspecialchars($fila['ip_usuario']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>⚠️ No se encontraron registros con esos filtros.</p>
        <?php endif; ?>
    </section>

    <section>
        <h3>📄 Paginación</h3>
        <p>Mostrando página <?= $pagina ?> de <?= $total_paginas ?> (<?= $total['total'] ?> registros)</p>
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="historial_bd.php?pagina=<?= $i . $params ?>" 
               <?= $i === $pagina ? 'style="font-weight:bold"' : '' ?>>
               [<?= $i ?>]
            </a>
        <?php endfor; ?>
    </section>
</main>

<footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
</footer>
</body>
</html>
