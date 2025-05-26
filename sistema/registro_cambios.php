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
    die("❌ Acceso denegado: Solo administradores pueden ver el historial.");
}

// Validar si se recibió el nombre de la tabla
if (!isset($_GET['tabla'])) {
    die("❌ Error: No se ha especificado una tabla.");
}

$tabla = htmlspecialchars($_GET['tabla']);

// Obtener los cambios registrados en la tabla específica
$sql = "SELECT * FROM historial_bd WHERE tabla_afectada = '$tabla' ORDER BY fecha DESC";
$resultado = $conn->query($sql);

if (!$resultado) {
    error_log("❌ Error en consulta SQL: " . $conn->error);
    die("❌ Error al obtener cambios: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Cambios - <?= $tabla ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base.css">
</head>
<body>
    <header>
        <h1>📝 Historial de Cambios en <?= $tabla ?></h1>
        <nav class="nav-bar">
            <a href="admin_db.php" class="btn-nav">🏠 Panel Principal</a>
            <a href="editar_tabla.php?tabla=<?= $tabla ?>" class="btn-nav">⬅️ Volver a la Tabla</a>
        </nav>
    </header>

    <main>
        <h3>Registros de Modificaciones</h3>
        <table border="1">
            <tr>
                <th>Usuario</th>
                <th>Acción</th>
                <th>Fecha</th>
                <th>IP</th>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($fila['usuario']) ?></td>
                    <td><?= htmlspecialchars($fila['accion']) ?></td>
                    <td><?= htmlspecialchars($fila['fecha']) ?></td>
                    <td><?= htmlspecialchars($fila['ip_usuario']) ?></td>
                </tr>
            <?php } ?>
        </table>
    </main>

    <footer>
        <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
