<?php
    //  archivo :   modulos/orden_trabajo/views/partials/viajes_orden.php

// Activar la visualización de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../includes/conexion.php';

// Verificar la conexión a la base de datos
if (!$conn) {
    die("❌ Error: No se pudo conectar a la base de datos.");
}

// Validar el parámetro GET 'orden_trabajo_id'
if (
    !isset($_GET['orden_trabajo_id']) || 
    empty($_GET['orden_trabajo_id']) || 
    !is_numeric($_GET['orden_trabajo_id'])
) {
    die("❌ Error: No se ha especificado una orden válida.");
}

$ordenID = intval($_GET['orden_trabajo_id']);

// Consulta para obtener los viajes relacionados a la orden
$sqlViajes = "SELECT 
                vo.id, 
                vo.fecha_salida, 
                v.placa AS vehiculo, 
                COALESCE(CONCAT(c.nombres, ' ', c.apellidos), 'Sin asignar') AS conductor, 
                lo.nombre AS origen, 
                ld.nombre AS destino, 
                do.nombre AS distrito_origen, 
                dd.nombre AS distrito_destino
              FROM viajes_orden vo
              LEFT JOIN ordenes_vehiculo ov ON vo.orden_vehiculo_id = ov.id
              LEFT JOIN vehiculos v ON ov.vehiculo_id = v.id
              LEFT JOIN asignaciones_conductor ac ON v.id = ac.vehiculo_id
              LEFT JOIN conductores c ON ac.conductor_id = c.id
              LEFT JOIN lugares lo ON vo.origen_id = lo.id
              LEFT JOIN lugares ld ON vo.destino_id = ld.id
              LEFT JOIN distritos do ON lo.distrito_id = do.id
              LEFT JOIN distritos dd ON ld.distrito_id = dd.id
              WHERE ov.orden_trabajo_id = ?";

$stmtViajes = $conn->prepare($sqlViajes);

// Verificar si la consulta se preparó correctamente
if (!$stmtViajes) {
    die("❌ Error al preparar la consulta de viajes: " . $conn->error);
}

// Vincular el parámetro y ejecutar la consulta
$stmtViajes->bind_param("i", $ordenID);
$stmtViajes->execute();
$resultViajes = $stmtViajes->get_result();

// Mostrar mensaje si no hay viajes registrados
if ($resultViajes->num_rows === 0) {
    echo "<div class='alert alert-warning'>🚨 No hay viajes registrados para esta orden.</div>";
}
?>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Fecha</th>
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
                <td><?= htmlspecialchars($viaje['fecha_salida']) ?></td>
                <td><?= htmlspecialchars($viaje['vehiculo']) ?></td>
                <td><?= htmlspecialchars($viaje['conductor']) ?></td>
                <td><?= htmlspecialchars($viaje['origen']) ?></td>
                <td><?= htmlspecialchars($viaje['distrito_origen']) ?></td>
                <td><?= htmlspecialchars($viaje['destino']) ?></td>
                <td><?= htmlspecialchars($viaje['distrito_destino']) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
