<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once __DIR__ . '/../modelo.php';

$conn = getConnection();
$estadoActivo = getEstadoId($conn, 'activo');
$asignacionesActivas = getAsignacionesActivas($conn, $estadoActivo);
?>

<?php if ($asignacionesActivas->num_rows > 0): ?>
  <table id="tablaActivas" class="table table-bordered table-hover text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>Tracto</th>
        <th>Remolque</th>
        <th>Conductor</th>
        <th>Fecha Asignación</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $asignacionesActivas->fetch_assoc()): ?>
        <tr>
          <td><?= sanitize($row['tracto_placa']) ?></td>
          <td><?= sanitize($row['remolque_placa']) ?></td>
          <td><?= sanitize($row['conductor']) ?></td>
          <td><?= sanitize($row['fecha_inicio']) ?></td>
          <td><?= sanitize($row['estado']) ?></td>
          <td class="text-center">
        	<button class="btn btn-danger btn-sm btn-finalizar" data-finalizar-id="<?= sanitize($row['id']) ?>">
            	<i class="fas fa-times-circle"></i> Finalizar
			</button>
			<button class="btn btn-warning btn-sm btn-editar" data-id="<?= $row['id'] ?>">
				<i class="fas fa-edit"></i> Editar
			</button>
			<button class="btn btn-secondary btn-sm btn-cancelar" data-id="<?= $row['id'] ?>">
				<i class="fas fa-ban"></i> Cancelar
			</button>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
<?php else: ?>
  <div class="alert alert-warning text-center">
    <i class="fas fa-exclamation-circle me-2"></i> No hay asignaciones activas.
  </div>
<?php endif; ?>