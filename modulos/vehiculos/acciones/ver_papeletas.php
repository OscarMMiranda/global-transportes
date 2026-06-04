<?php
    // archivo: /modulos/vehiculos/acciones/ver_papeletas.php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

$idVehiculo = isset($_POST['id']) ? intval($_POST['id']) : 0;

/* ============================================================
   FUNCIONES DE FORMATO
   ============================================================ */

function badgeEstado($estado) {
    $estado = strtolower(trim($estado));

    if ($estado == 'pendiente') 
        return '<span class="badge" style="background:#ffc107; color:#000;">Pendiente</span>';

    if ($estado == 'en reclamo') 
        return '<span class="badge" style="background:#17a2b8;">En reclamo</span>';

    if ($estado == 'pagada') 
        return '<span class="badge" style="background:#28a745;">Pagada</span>';

    if ($estado == 'anulada') 
        return '<span class="badge" style="background:#dc3545;">Anulada</span>';

    return '<span class="badge" style="background:#6c757d;">' . htmlspecialchars($estado) . '</span>';
}

function formatFecha($f) {
    if (!$f || $f == '0000-00-00') return '-';
    $p = explode('-', $f);
    return $p[2] . '/' . $p[1] . '/' . $p[0];
}

/* ============================================================
   SQL PRINCIPAL: PAPELETAS DEL VEHÍCULO
   ============================================================ */

$sql = "
    SELECT 
        p.id,
        p.fecha_infraccion,
        p.fecha_vencimiento,
        p.fecha_pago,
        p.lugar,
        p.descripcion,
        p.monto,
        p.monto_descuento,
        p.monto_pagado,

        ie.nombre AS entidad_nombre,
        i.codigo AS infraccion_codigo,
        i.descripcion AS infraccion_desc,
        i.gravedad,
        i.puntos,

        pe.nombre AS estado_nombre,

        c.nombres AS conductor_nombre,
        c.apellidos AS conductor_apellidos

    FROM papeletas p
    JOIN entidad_emisora ie ON p.entidad_emisora_id = ie.id
    JOIN infracciones i ON p.infraccion_id = i.id
    JOIN papeleta_estado pe ON p.estado_id = pe.id
    LEFT JOIN conductores c ON p.conductor_id = c.id

    WHERE p.vehiculo_id = $idVehiculo
      AND p.eliminado = 0

    ORDER BY p.fecha_infraccion DESC, p.id DESC
";

$q = mysqli_query($conn, $sql);

/* ============================================================
   HTML
   ============================================================ */

ob_start();
?>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Entidad</th>
            <th>Infracción</th>
            <th>Conductor</th>
            <th>Monto</th>
            <th>Vence</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($q && mysqli_num_rows($q) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($q)): ?>

                <?php
                    $conductor = ($row['conductor_nombre'])
                        ? $row['conductor_nombre'] . ' ' . $row['conductor_apellidos']
                        : '-';

                    $montoFinal = $row['monto'] - $row['monto_descuento'];
                ?>

                <tr>
                    <td><?= formatFecha($row['fecha_infraccion']) ?></td>

                    <td>
                        <strong><?= $row['entidad_nombre'] ?></strong><br>
                        <small class="text-muted"><?= $row['lugar'] ?></small>
                    </td>

                    <td>
                        <strong><?= $row['infraccion_codigo'] ?></strong><br>
                        <small><?= htmlspecialchars($row['infraccion_desc']) ?></small><br>
                        <span class="badge bg-dark"><?= $row['gravedad'] ?></span>
                        <?php if ($row['puntos'] > 0): ?>
                            <span class="badge bg-danger">Puntos: <?= $row['puntos'] ?></span>
                        <?php endif; ?>
                    </td>

                    <td><?= $conductor ?></td>

                    <td>
                        <strong>S/ <?= number_format($montoFinal, 2) ?></strong><br>
                        <?php if ($row['monto_descuento'] > 0): ?>
                            <small class="text-success">Descuento: S/ <?= number_format($row['monto_descuento'], 2) ?></small>
                        <?php endif; ?>
                        <?php if ($row['monto_pagado'] > 0): ?>
                            <br><small class="text-primary">Pagado: S/ <?= number_format($row['monto_pagado'], 2) ?></small>
                        <?php endif; ?>
                    </td>

                    <td><?= formatFecha($row['fecha_vencimiento']) ?></td>

                    <td><?= badgeEstado($row['estado_nombre']) ?></td>
                </tr>

            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center text-muted">
                    No hay papeletas registradas para este vehículo.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php
$html = ob_get_clean();

echo json_encode([
    'success' => true,
    'html' => $html
]);
exit;
