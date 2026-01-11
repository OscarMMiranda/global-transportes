<?php
// archivo: /modulos/vehiculos/vistas/historial.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Validación del ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<div class='alert alert-danger'>ID inválido</div>");
}

$id = intval($_GET['id']);

// Conexión correcta
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";

$conn = getConnection();
if (!$conn) {
    die("<div class='alert alert-danger'>Error de conexión a la base de datos.</div>");
}

// Consulta del historial REAL
$sql = "
    SELECT 
        fecha,
        usuario_id,
        estado_anterior,
        estado_nuevo,
        motivo,
        ip_origen,
        user_agent
    FROM vehiculo_historial
    WHERE vehiculo_id = $id
    ORDER BY fecha DESC
";

$result = $conn->query($sql);

// Función fuera del loop (PHP 5.6 compatible)
function badgeEstado($estado) {
    switch ($estado) {
        case 'activo': return '<span class="badge bg-success">ACTIVO</span>';
        case 'inactivo': return '<span class="badge bg-secondary">INACTIVO</span>';
        case 'asignado': return '<span class="badge bg-primary">ASIGNADO</span>';
        default: return '<span class="badge bg-dark">—</span>';
    }
}
?>

<h5 class="section-title mb-3">
    <i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>
    Historial de cambios
</h5>

<?php if (!$result || $result->num_rows === 0): ?>

    <div class="alert alert-info">
        No hay registros en el historial.
    </div>

<?php else: ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle historial-table">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Estado anterior</th>
                    <th>Estado nuevo</th>
                    <th>Motivo</th>
                    <th>IP</th>
                    <th>Agente</th>
                </tr>
            </thead>
            <tbody>

                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['fecha']) ?></td>

                        <td>
                            <span class="text-muted">ID <?= htmlspecialchars($row['usuario_id']) ?></span>
                        </td>

                        <td><?= badgeEstado($row['estado_anterior']) ?></td>
                        <td><?= badgeEstado($row['estado_nuevo']) ?></td>

                        <td>
                            <?php if (!empty($row['motivo'])): ?>
                                <div class="p-2 bg-light border rounded small">
                                    <?= nl2br(htmlspecialchars($row['motivo'])) ?>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <span class="small text-muted"><?= htmlspecialchars($row['ip_origen']) ?></span>
                        </td>

                        <td>
                            <span class="small text-muted d-inline-block" style="max-width:180px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <?= htmlspecialchars($row['user_agent']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>

            </tbody>
        </table>
    </div>

<?php endif; ?>