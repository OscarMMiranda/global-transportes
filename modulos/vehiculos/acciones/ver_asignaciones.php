<?php
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

    if ($estado == 'activo') return '<span class="badge" style="background:#28a745;">Activo</span>';
    if ($estado == 'finalizado') return '<span class="badge" style="background:#007bff;">Finalizado</span>';
    if ($estado == 'pendiente') return '<span class="badge" style="background:#fd7e14;">Pendiente</span>';
    if ($estado == 'anulado') return '<span class="badge" style="background:#dc3545;">Anulado</span>';

    return '<span class="badge" style="background:#6c757d;">' . htmlspecialchars($estado) . '</span>';
}

function formatFecha($f) {
    if (!$f || $f == '0000-00-00') return '-';
    $p = explode('-', $f);
    return $p[2] . '/' . $p[1] . '/' . $p[0];
}

/* ============================================================
   SQL FINAL
   ============================================================ */

$sql = "
    SELECT 
        ac.id,
        ac.fecha_inicio,
        ac.fecha_fin,

        c.nombres AS conductor_nombres,
        c.apellidos AS conductor_apellidos,

        vt.placa AS tracto_placa,
        vr.placa AS carreta_placa,

        ea.nombre AS estado_nombre

    FROM asignaciones_conductor ac
    JOIN conductores c ON ac.conductor_id = c.id
    JOIN vehiculos vt ON ac.vehiculo_tracto_id = vt.id
    JOIN vehiculos vr ON ac.vehiculo_remolque_id = vr.id
    LEFT JOIN estado_asignacion ea ON ac.estado_id = ea.id

    WHERE ac.vehiculo_tracto_id = $idVehiculo
       OR ac.vehiculo_remolque_id = $idVehiculo

    ORDER BY ac.fecha_inicio DESC
";

$q = mysqli_query($conn, $sql);

/* ============================================================
   HTML
   ============================================================ */

ob_start();
?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Conductor</th>
            <th>Tracto</th>
            <th>Carreta</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($q && mysqli_num_rows($q) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>

                    <td>
                        <span style="font-weight:bold; color:#333;">
                            🚹 <?= $row['conductor_nombres'] . ' ' . $row['conductor_apellidos'] ?>
                        </span>
                    </td>

                    <td><span style="font-weight:bold;"><?= $row['tracto_placa'] ?></span></td>
                    <td><span style="font-weight:bold;"><?= $row['carreta_placa'] ?></span></td>

                    <td><?= formatFecha($row['fecha_inicio']) ?></td>
                    <td><?= formatFecha($row['fecha_fin']) ?></td>

                    <td><?= badgeEstado($row['estado_nombre']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center text-muted">
                    Sin asignaciones registradas.
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
