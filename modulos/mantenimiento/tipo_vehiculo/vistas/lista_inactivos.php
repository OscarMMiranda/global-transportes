<!-- archivo: /modulos/mantenimiento/tipo_vehiculo/vista/lista_inactivos.php -->

<?php if (!empty($datos)): ?>
  <h5 class="mt-1 mb-2 text-danger">
    <i class="fas fa-ban me-1"></i> Vehículos Eliminados
  </h5>

  <table id="tablaInactivos" class="table table-bordered table-hover">
    <thead class="table-light align-middle">
      <tr>
        <th style="width: 60px;">#</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Categoría</th>
        <th class="text-center" style="width: 150px;">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($datos as $vehiculo): ?>
        <tr class="table-danger">
          <td><?= $vehiculo['id'] ?></td>
          <td><?= htmlspecialchars($vehiculo['nombre']) ?></td>
          <td><?= htmlspecialchars($vehiculo['descripcion']) ?></td>
          <td>
            <?= isset($vehiculo['categoria_nombre']) 
              ? htmlspecialchars($vehiculo['categoria_nombre']) 
              : '<em class="text-muted">Sin categoría</em>' ?>
          </td>
          <td class="text-center">
            <a 
              href="index.php?action=reactivar&id=<?= $vehiculo['id'] ?>" 
              class="btn btn-sm btn-warning"
              title="Restaurar vehículo"
              onclick="return confirm('¿Restaurar este tipo de vehículo?')"
            >
              <i class="fas fa-undo"></i>
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

<?php else: ?>
  <div class="alert alert-secondary text-center shadow-sm py-3 rounded-3">
    <i class="fas fa-trash-alt me-2"></i> No hay vehículos eliminados.
  </div>
<?php endif; ?>