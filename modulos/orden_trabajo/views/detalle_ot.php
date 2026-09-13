<?php
// archivo: /modulos/orden_trabajo/views/detalle_ot.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$ot_id = intval($_GET['id']);

// ======================================================
// OBTENER DATOS DE LA OT
// ======================================================
$sql = "
SELECT 
    ot.id,
    ot.numero_ot,
    ot.fecha,
    ot.semana_ot,
    c.nombre AS cliente,
    e.nombre AS empresa,
    t.nombre AS tipo_ot,
    est.nombre AS estado
FROM ordenes_trabajo ot
LEFT JOIN clientes c ON c.id = ot.cliente_id
LEFT JOIN empresa e ON e.id = ot.empresa_id
LEFT JOIN tipo_ot t ON t.id = ot.tipo_ot_id
LEFT JOIN estado_orden_trabajo est ON est.id = ot.estado_id
WHERE ot.id = $ot_id
LIMIT 1
";

$res = $conn->query($sql);

if (!$res || $res->num_rows === 0) {
    echo "<div class='alert alert-danger'>No se encontró la Orden de Trabajo.</div>";
    exit;
}

$ot = $res->fetch_assoc();
?>

<div class="container">

    <!-- ====================================================== -->
    <!-- CABECERA DE LA OT -->
    <!-- ====================================================== -->
    <h3>Orden de Trabajo N° <?php echo $ot['numero_ot']; ?></h3>

    <table class="table table-bordered">
        <tr>
            <th>Fecha</th>
            <td><?php echo $ot['fecha']; ?></td>
        </tr>
        <tr>
            <th>Semana</th>
            <td><?php echo $ot['semana_ot']; ?></td>
        </tr>
        <tr>
            <th>Cliente</th>
            <td><?php echo $ot['cliente']; ?></td>
        </tr>
        <tr>
            <th>Empresa</th>
            <td><?php echo $ot['empresa']; ?></td>
        </tr>
        <tr>
            <th>Tipo OT</th>
            <td><?php echo $ot['tipo_ot']; ?></td>
        </tr>
        <tr>
            <th>Estado</th>
            <td><?php echo $ot['estado']; ?></td>
        </tr>
    </table>

    <!-- ====================================================== -->
    <!-- BOTÓN REGISTRAR VIAJE -->
    <!-- ====================================================== -->
    <button class="btn btn-primary" onclick="abrirRegistrarViaje(<?php echo $ot_id; ?>)">
        Registrar Viaje
    </button>

    <br><br>

    <!-- ====================================================== -->
    <!-- TABLA DE VIAJES -->
    <!-- ====================================================== -->
    <h4>Viajes Registrados</h4>

    <table class="table table-striped" id="tablaViajesOT">
        <thead>
            <tr>
                <th>N° Viaje</th>
                <th>Fecha</th>
                <th>Semana</th>
                <th>Vehículo</th>
                <th>Conductor</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

</div>

<!-- ====================================================== -->
<!-- CARGAR VIAJES AL INICIAR -->
<!-- ====================================================== -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    cargarViajesOT(<?php echo $ot_id; ?>);
});
</script>

<!-- ====================================================== -->
<!-- MODAL REGISTRAR VIAJE -->
<!-- ====================================================== -->
<?php include __DIR__ . '/../modales/modal_registrar_viaje_nuevo.php'; ?>
