<hr>
<h5>Lista de Agencias Aduanas</h5>
<table class="table table-hover align-middle">
  <thead class="table-light">
    <tr>
      <th>Nombre</th>
      <th>RUC</th>
      <th>Dirección</th>
      <th>Distrito</th>
      <th>Provincia</th>
      <th>Departamento</th>
      <th>Estado</th>
      <th>Fecha Creación</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($agencias as $a): ?>
      <tr>
        <td><?= htmlspecialchars($a['nombre']) ?></td>
        <td><?= htmlspecialchars($a['ruc']) ?></td>
        <td><?= htmlspecialchars($a['direccion']) ?></td>
        <td><?= htmlspecialchars($a['distrito_nombre']) ?></td>
        <td><?= htmlspecialchars($a['provincia_nombre']) ?></td>
        <td><?= htmlspecialchars($a['departamento_nombre']) ?></td>
        <td>
          <?= $a['estado']
             ? '<span class="badge bg-success">Activo</span>'
             : '<span class="badge bg-secondary">Eliminado</span>' 
          ?>
        </td>
        <td><?= htmlspecialchars($a['fecha_creacion']) ?></td>
        <td>
          <?php if ($a['estado']): ?>
            <a href="?editar=<?= $a['id'] ?>" class="btn btn-sm btn-warning">✎</a>
            <a href="?eliminar=<?= $a['id'] ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('¿Eliminar esta agencia?')">🗑</a>
          <?php else: ?>
            <a href="?reactivar=<?= $a['id'] ?>" class="btn btn-sm btn-success">⟳</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
