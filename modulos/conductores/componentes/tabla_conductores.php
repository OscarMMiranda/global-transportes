<?php
// archivo: /modulos/conductores/componentes/tabla_conductores.php

if (!isset($conductores) || !is_array($conductores)) {
  echo '<div class="alert alert-warning">⚠️ No se encontraron conductores.</div>';
  return;
}
?>

<table id="<?= $tablaId ?>" class="table table-bordered table-hover table-sm align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>Apellidos y Nombres</th>
      <th>DNI</th>
      <th>Licencia</th>
      <th>Teléfono</th>
      <th>Estado</th>
      <th class="text-center">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($conductores as $i => $c): ?>
      <tr data-id="<?= $c['id'] ?>">
        <td><?= $i + 1 ?></td>
        <td><?= htmlspecialchars($c['apellidos'] . ', ' . $c['nombres']) ?></td>
        <td><?= htmlspecialchars($c['dni']) ?></td>
        <td><?= htmlspecialchars($c['licencia_conducir']) ?></td>
        <td><?= $c['telefono'] ? htmlspecialchars($c['telefono']) : '—' ?></td>
        <td>
          <?php if ((int)$c['activo'] === 1): ?>
            <span class="badge bg-success" title="Conductor activo">Activo</span>
          <?php else: ?>
            <span class="badge bg-secondary" title="Conductor inactivo">Inactivo</span>
          <?php endif; ?>
        </td>
        <td class="text-center">
          <!-- Botones según estado -->
          <button class="btn btn-sm btn-info btn-view" title="Ver" data-id="<?= $c['id'] ?>">
            <i class="fa fa-eye"></i>
          </button>

          <?php if ((int)$c['activo'] === 1): ?>
            <button class="btn btn-sm btn-primary btn-edit" title="Editar" data-id="<?= $c['id'] ?>">
              <i class="fa fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-warning btn-soft-delete" title="Desactivar" data-id="<?= $c['id'] ?>">
              <i class="fa fa-trash-can"></i>
            </button>
          <?php else: ?>
            <button class="btn btn-sm btn-success btn-restore" title="Restaurar" data-id="<?= $c['id'] ?>">
              <i class="fa fa-rotate-left"></i>
            </button>
            <button class="btn btn-sm btn-danger btn-delete" title="Eliminar definitivo" data-id="<?= $c['id'] ?>">
              <i class="fa fa-trash"></i>
            </button>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>