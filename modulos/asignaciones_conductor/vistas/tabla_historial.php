<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once __DIR__ . '/../modelo.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/modulos/asignaciones_conductor/funciones.php';


$conn = getConnection();
$estadoFinalizado = getEstadoId($conn, 'finalizado');
$historialAsignaciones = getHistorialAsignaciones($conn, $estadoFinalizado);
?>

<?php if ($historialAsignaciones->num_rows > 0): ?>
  <table id="tablaHistorial" class="table table-bordered table-hover text-center align-middle">
    <thead class="table-secondary">
      <tr>
        <th>Tracto</th>
        <th>Remolque</th>
        <th>Conductor</th>
        <th>Fecha Asignación</th>
        <th>Fecha Fin</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $historialAsignaciones->fetch_assoc()): ?>
        <tr>
          <td><?= sanitize($row['tracto_placa']) ?></td>
          <td><?= sanitize($row['remolque_placa']) ?></td>
          <td><?= sanitize($row['conductor']) ?></td>
          <td><?= sanitize($row['fecha_inicio']) ?></td>
          <td><?= sanitize($row['fecha_fin']) ?></td>
          <td><?= sanitize($row['estado']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
<?php else: ?>
  <div class="alert alert-info text-center">
    <i class="fas fa-info-circle me-2"></i> No hay historial de asignaciones.
  </div>
<?php endif; ?>