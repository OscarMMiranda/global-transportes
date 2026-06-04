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

    if ($estado == 'pendiente') return '<span class="badge" style="background:#ffc107; color:#000;">Pendiente</span>';
    if ($estado == 'en proceso') return '<span class="badge" style="background:#17a2b8;">En proceso</span>';
    if ($estado == 'finalizado') return '<span class="badge" style="background:#28a745;">Finalizado</span>';
    if ($estado == 'anulado') return '<span class="badge" style="background:#dc3545;">Anulado</span>';

    return '<span class="badge" style="background:#6c757d;">' . htmlspecialchars($estado) . '</span>';
}

function formatFecha($f) {
    if (!$f || $f == '0000-00-00') return '-';
    $p = explode('-', $f);
    return $p[2] . '/' . $p[1] . '/' . $p[0];
}

/* ============================================================
   SQL PRINCIPAL: MANTENIMIENTOS DEL VEHÍCULO
   ============================================================ */

$sql = "
    SELECT 
        m.id,
        m.fecha,
        m.descripcion,
        m.kilometraje,
        m.costo_total,

        mt.nombre AS tipo_nombre,
        me.nombre AS estado_nombre,

        p.nombre AS proveedor_nombre

    FROM mantenimientos m
    JOIN mantenimiento_tipo mt ON m.tipo_id = mt.id
    JOIN mantenimiento_estado me ON m.estado_id = me.id
    LEFT JOIN proveedores p ON m.proveedor_id = p.id

    WHERE m.vehiculo_id = $idVehiculo
      AND m.eliminado = 0

    ORDER BY m.fecha DESC, m.id DESC
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
            <th>Tipo</th>
            <th>Descripción</th>
            <th>Proveedor</th>
            <th>KM</th>
            <th>Costo</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($q && mysqli_num_rows($q) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($q)): ?>
                <tr>
                    <td><?= formatFecha($row['fecha']) ?></td>

                    <td><strong><?= $row['tipo_nombre'] ?></strong></td>

                    <td><?= nl2br(htmlspecialchars($row['descripcion'])) ?></td>

                    <td><?= $row['proveedor_nombre'] ? $row['proveedor_nombre'] : '-' ?></td>

                    <td><?= number_format($row['kilometraje']) ?></td>

                    <td>S/ <?= number_format($row['costo_total'], 2) ?></td>

                    <td><?= badgeEstado($row['estado_nombre']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center text-muted">
                    No hay mantenimientos registrados para este vehículo.
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
