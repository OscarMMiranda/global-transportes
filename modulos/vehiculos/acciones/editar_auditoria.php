<?php
// archivo: /modulos/vehiculos/acciones/editar_auditoria.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

$id = intval($_GET['id']);

// ============================================================
// CONSULTA HISTORIAL DEL VEHÍCULO
// ============================================================

$sql = "
    SELECT 
        h.id,
        h.vehiculo_id,
        h.tipo_origen,
        h.estado_anterior,
        h.estado_nuevo,
        h.motivo,
        h.usuario_id,
        h.ip_origen,
        h.user_agent,
        h.fecha,
        u.usuario AS usuario_nombre
    FROM vehiculo_historial h
    LEFT JOIN usuarios u ON u.id = h.usuario_id
    WHERE h.vehiculo_id = $id
    ORDER BY h.fecha DESC
";

$res = $conn->query($sql);

// Función segura PHP 5.6
function v($arr, $key) {
    return isset($arr[$key]) ? htmlspecialchars($arr[$key]) : "";
}
?>

<div class="container-fluid">

    <h6 class="fw-bold mt-2">Historial del Vehículo</h6>
    <hr>

    <?php if ($res && $res->num_rows > 0): ?>

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Tipo Origen</th>
                        <th>Estado Anterior</th>
                        <th>Estado Nuevo</th>
                        <th>Motivo</th>
                        <th>IP</th>
                        <th>User Agent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo v($row, 'fecha'); ?></td>
                            <td><?php echo v($row, 'usuario_nombre'); ?></td>
                            <td><?php echo v($row, 'tipo_origen'); ?></td>
                            <td><?php echo v($row, 'estado_anterior'); ?></td>
                            <td><?php echo v($row, 'estado_nuevo'); ?></td>
                            <td><?php echo nl2br(v($row, 'motivo')); ?></td>
                            <td><?php echo v($row, 'ip_origen'); ?></td>
                            <td><?php echo v($row, 'user_agent'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>

        <div class="alert alert-info text-center">
            No existen registros de historial para este vehículo.
        </div>

    <?php endif; ?>

</div>
