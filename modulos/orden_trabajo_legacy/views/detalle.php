<?php
    //  archivo :   /modulos/orden_trabajo/detalle_orden.php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once '../../includes/conexion.php';

// Validar que se reciba un ID de orden válido
if (!isset($_GET['orden_trabajo_id']) || empty($_GET['orden_trabajo_id']) || !is_numeric($_GET['orden_trabajo_id'])) {
    header("Location: orden_trabajo.php?error=❌ Error: Orden no válida");
    exit();
}

$ordenID = intval($_GET['orden_trabajo_id']);
$_SESSION['orden_trabajo_id'] = $ordenID; // Guardamos el ID en la sesión para pasarlo al formulario

// Consulta para obtener los datos de la orden de trabajo
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
$stmtOrden->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Orden</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/orden_trabajo.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <!-- CABECERA: Datos generales de la orden de trabajo -->
        <h2 class="text-center text-primary mb-4">📄 Orden de trabajo: <?= htmlspecialchars($orden['numero_ot']); ?></h2>

        <div class="card shadow-lg mb-4">
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr><th>Empresa</th><td><?= htmlspecialchars($orden['empresa']); ?></td></tr>
                        <tr><th>Fecha</th><td><?= htmlspecialchars($orden['fecha']); ?></td></tr>
                        <tr><th>Cliente</th><td><?= htmlspecialchars($orden['cliente']); ?></td></tr>
                        <tr><th>Tipo de OT</th><td><?= htmlspecialchars($orden['tipo_ot']); ?></td></tr>
                        <tr><th>Estado</th><td><?= htmlspecialchars($orden['estado']); ?></td></tr>
                        <tr><th>Orden Cliente</th><td><?= htmlspecialchars($orden['oc_cliente']); ?></td></tr>
                        <?php if ($orden['tipo_ot'] === "Importación") { ?>
                        <tr><th>Número DAM</th><td><?= htmlspecialchars($orden['numero_dam']); ?></td></tr>
                        <?php } elseif ($orden['tipo_ot'] === "Exportación") { ?>
                        <tr><th>Número de Booking</th><td><?= htmlspecialchars($orden['numero_booking']); ?></td></tr>
                        <?php } elseif ($orden['tipo_ot'] === "Nacional") { ?>
                        <tr><th>Otros</th><td><?= htmlspecialchars($orden['otros']); ?></td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    
        <!-- LISTA DE VIAJES REGISTRADOS -->
        <h3 class="text-primary mt-4">🚚 Viajes registrados</h3>
        <div id="viajes_orden">
            <?php 
            if (file_exists(__DIR__ . '/viajes_orden.php')) {
                include __DIR__ . '/viajes_orden.php';
            } else {
                echo "<div class='alert alert-warning'>🚨 Error: No se encontró el archivo de viajes.</div>";
            }
            ?>
        </div>
    
        <!-- FORMULARIO DE REGISTRO DE VIAJE SIN MODAL -->
        <h3 class="text-primary mt-4">✍️ Registrar un nuevo viaje</h3>
        <div class="card shadow-lg p-3">
            <?php 
            if (file_exists(__DIR__ . '/formulario_viaje.php')) {
                include __DIR__ . '/formulario_viaje.php';
            } else {
                echo "<div class='alert alert-warning'>🚨 Error: No se encontró el formulario de viaje.</div>";
            }
            ?>
        </div>

        <!-- Botón para volver al listado de órdenes -->
        <div class="mt-3 text-center">
            <a href="orden_trabajo.php" class="btn btn-secondary">⬅️ Volver a Órdenes</a>
        </div>
    </div>
  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
