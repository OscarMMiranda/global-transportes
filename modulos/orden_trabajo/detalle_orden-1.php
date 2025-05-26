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
    echo "<script>alert('❌ Error: No se ha especificado una orden válida.'); window.location.href='orden_trabajo.php';</script>";
    exit();
}

$ordenID = $_GET['orden_trabajo_id'];

$sqlOrden = "SELECT ot.numero_ot, ot.fecha, c.nombre AS cliente, e.razon_social AS empresa, 
                    eo.nombre AS estado, tot.nombre AS tipo_ot
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
    echo "<script>alert('❌ Error: La orden no existe.'); window.location.href='orden_trabajo.php';</script>";
    exit();
}

$orden = $resultOrden->fetch_assoc();

$sqlViajes = "SELECT vo.id, vo.fecha_salida, v.placa AS vehiculo, c.nombre AS conductor,
                     lo.nombre AS origen, ld.nombre AS destino, 
                     do.nombre AS distrito_origen, dd.nombre AS distrito_destino
              FROM viajes_orden vo
              LEFT JOIN vehiculos v ON vo.vehiculo_id = v.id
              LEFT JOIN asignaciones_conductor ac ON v.id = ac.vehiculo_id AND ac.activo = 1
              LEFT JOIN conductores c ON ac.conductor_id = c.id
              LEFT JOIN lugares lo ON vo.origen_id = lo.id
              LEFT JOIN lugares ld ON vo.destino_id = ld.id
              LEFT JOIN distritos do ON lo.distrito_id = do.id
              LEFT JOIN distritos dd ON ld.distrito_id = dd.id
              WHERE vo.orden_trabajo_id = ?";
$stmtViajes = $conn->prepare($sqlViajes);
$stmtViajes->bind_param("i", $ordenID);
$stmtViajes->execute();
$resultViajes = $stmtViajes->get_result();

$sqlVehiculos = "SELECT v.id, v.placa, c.nombre AS conductor 
                 FROM vehiculos v
                 LEFT JOIN asignaciones_conductor ac ON v.id = ac.vehiculo_id AND ac.activo = 1
                 LEFT JOIN conductores c ON ac.conductor_id = c.id
                 ORDER BY v.placa ASC";
$resultVehiculos = $conn->query($sqlVehiculos);
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
        <h2 class="text-center text-primary mb-4">📄 Orden de trabajo: <?= $orden['numero_ot'] ?></h2>
        
        <div class="card shadow-lg">
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr><th>Empresa</th><td><?= $orden['empresa'] ?></td></tr>
                        <tr><th>Fecha</th><td><?= $orden['fecha'] ?></td></tr>
                        <tr><th>Cliente</th><td><?= $orden['cliente'] ?></td></tr>
                        <tr><th>Tipo de OT</th><td><?= $orden['tipo_ot'] ?></td></tr>
                        <tr><th>Estado</th><td><?= $orden['estado'] ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <h3 class="text-primary mt-4">🚚 Viajes registrados</h3>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Fecha de salida</th>
                    <th>Vehículo</th>
                    <th>Conductor</th>
                    <th>Origen</th>
                    <th>Distrito Origen</th>
                    <th>Destino</th>
                    <th>Distrito Destino</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($viaje = $resultViajes->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $viaje['fecha_salida'] ?></td>
                        <td><?= $viaje['vehiculo'] ?></td>
                        <td><?= $viaje['conductor'] ?></td>
                        <td><?= $viaje['origen'] ?></td>
                        <td><?= $viaje['distrito_origen'] ?></td>
                        <td><?= $viaje['destino'] ?></td>
                        <td><?= $viaje['distrito_destino'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3 class="text-primary mt-4">✍️ Registrar un nuevo viaje</h3>
        <form method="POST" action="registrar_viaje.php" class="mb-4">
            <input type="hidden" name="orden_trabajo_id" value="<?= $ordenID ?>">

            <div class="mb-3 d-flex align-items-center">
                <label for="vehiculo_id" class="me-2">Vehículo:</label>
                <select name="vehiculo_id" id="vehiculo_id" class="form-control w-25 me-2" required onchange="actualizarConductor('vehiculo_id', 'conductor')">
                    <option value="">-- Selecciona un vehículo --</option>
                    <?php while ($vehiculo = $resultVehiculos->fetch_assoc()) { ?>
                        <option value="<?= $vehiculo['id'] ?>" data-conductor="<?= $vehiculo['conductor'] ?>"><?= $vehiculo['placa'] ?></option>
                    <?php } ?>
                </select>
                <span id="conductor" class="text-muted">Conductor: No asignado</span>
            </div>

            <script>
            function actualizarConductor(selectId, conductorId) {
                var select = document.getElementById(selectId);
                var conductorTexto = select.options[select.selectedIndex].getAttribute('data-conductor');
                document.getElementById(conductorId).innerText = conductorTexto ? "Conductor: " + conductorTexto : "Conductor: No asignado";
            }
            </script>

            <button type="submit" class="btn btn-success">🚀 Guardar viaje</button>
        </form>

        <div class="mt-3 text-center">
            <a href="orden_trabajo.php" class="btn btn-secondary">⬅️ Volver a Órdenes</a>
        </div>
    </div>
</body>
</html>
