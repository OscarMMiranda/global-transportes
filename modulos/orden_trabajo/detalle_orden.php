<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once '../../includes/conexion.php';

if (!$conn) {
    die("❌ Error: No se pudo conectar a la base de datos.");
}

if (!isset($_GET['orden_trabajo_id']) || empty($_GET['orden_trabajo_id']) || !is_numeric($_GET['orden_trabajo_id'])) {
    header("Location: orden_trabajo.php?error=❌ Error: Orden no válida");
    exit();
}

$ordenID = $_GET['orden_trabajo_id'];

$sqlOrden = "SELECT ot.numero_ot, ot.fecha, ot.oc_cliente, ot.numero_dam, ot.numero_booking, ot.otros,
                    c.nombre AS cliente, e.razon_social AS empresa, eo.nombre AS estado, tot.nombre AS tipo_ot
             FROM ordenes_trabajo ot
             LEFT JOIN clientes c ON ot.cliente_id = c.id
             LEFT JOIN empresa e ON ot.empresa_id = e.id
             LEFT JOIN estado_orden_trabajo eo ON ot.estado_ot = eo.id
             LEFT JOIN tipo_ot tot ON ot.tipo_ot_id = tot.id
             WHERE ot.id = ?";
$stmtOrden = $conn->prepare($sqlOrden);
$stmtOrden->bind_param("i", $ordenID);
$stmtOrden->execute();
$resultOrden = $stmtOrden->get_result();

if ($resultOrden->num_rows === 0) {
    header("Location: orden_trabajo.php?error=❌ Error: La orden no existe");
    exit();
}

$orden = $resultOrden->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Orden</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center text-primary mb-4">📄 Orden de trabajo: <?= htmlspecialchars($orden['numero_ot']) ?></h2>

        <div class="card shadow-lg">
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr><th>Empresa</th><td><?= htmlspecialchars($orden['empresa']) ?></td></tr>
                        <tr><th>Fecha</th><td><?= htmlspecialchars($orden['fecha']) ?></td></tr>
                        <tr><th>Cliente</th><td><?= htmlspecialchars($orden['cliente']) ?></td></tr>
                        <tr><th>Tipo de OT</th><td><?= htmlspecialchars($orden['tipo_ot']) ?></td></tr>
                        <tr><th>Estado</th><td><?= htmlspecialchars($orden['estado']) ?></td></tr>
                        <tr><th>Orden Cliente</th><td><?= htmlspecialchars($orden['oc_cliente']) ?></td></tr>

                        <!-- Se muestra el campo correspondiente al tipo de orden -->
                        <?php if ($orden['tipo_ot'] === "Importación") { ?>
                            <tr><th>Número DAM</th><td><?= htmlspecialchars($orden['numero_dam']) ?></td></tr>
                        <?php } elseif ($orden['tipo_ot'] === "Exportación") { ?>
                            <tr><th>Número de Booking</th><td><?= htmlspecialchars($orden['numero_booking']) ?></td></tr>
                        <?php } elseif ($orden['tipo_ot'] === "Nacional") { ?>
                            <tr><th>Otros</th><td><?= htmlspecialchars($orden['otros']) ?></td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h3 class="text-primary mt-4">🚚 Viajes registrados</h3>

        <!-- Verificación antes de incluir `viajes_orden.php` -->
        <div id="viajes_orden">
            <?php 
            if (file_exists('viajes_orden.php')) {
                include 'viajes_orden.php'; 
            } else {
                echo "<div class='alert alert-warning'>🚨 Error: No se encontró el archivo de viajes.</div>";
            }
            ?>
        </div>

        <h3 class="text-primary mt-4">✍️ Registrar un nuevo viaje</h3>

        <!-- Verificación antes de incluir `formulario_viaje.php` -->
        <div id="formulario_viaje">
            <?php 
            if (file_exists('formulario_viaje.php')) {
                include 'formulario_viaje.php';
            } else {
                echo "<div class='alert alert-warning'>🚨 Error: No se encontró el formulario de viaje.</div>";
            }
            ?>
        </div>

        <div class="mt-3 text-center">
            <a href="orden_trabajo.php" class="btn btn-secondary">⬅️ Volver a Órdenes</a>
        </div>
    </div>
</body>
</html>
