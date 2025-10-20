<?php
// archivo: /modulos/mantenimiento/zonas/componentes/tabla_subzonas.php

if (!isset($subzonas) || !is_array($subzonas) || count($subzonas) === 0) {
  echo '<div class="alert alert-warning">No hay rutas registradas en el sistema.</div>';
  return;
}
?>

<table class="table table-bordered table-striped align-middle">
  <thead class="table-light">
    <tr>
      <th>ID</th>
      <th>Zona</th>
      <th>Origen</th>
      <th>Destino</th>
      <th>Kilometraje</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($subzonas as $z): ?>
    <tr>
      <td><?= $z['id'] ?></td>
      <td><?= htmlspecialchars($z['zona_padre']) ?></td>
      <td>
        <?= htmlspecialchars($z['departamento_origen']) ?> /
        <?= htmlspecialchars($z['provincia_origen']) ?> /
        <?= htmlspecialchars($z['distrito_origen']) ?>
      </td>
      <td>
        <?= htmlspecialchars($z['departamento_destino']) ?> /
        <?= htmlspecialchars($z['provincia_destino']) ?> /
        <?= htmlspecialchars($z['distrito_destino']) ?>
      </td>
      <td><?= $z['kilometros'] !== null ? number_format($z['kilometros'], 2) . ' km' : '—' ?></td>
      <td class="text-center"><?= $z['estado'] ? '✅' : '❌' ?></td>
      <td class="text-center">
        <a href="index.php?id=<?= $z['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
        <a href="index.php?eliminar=<?= $z['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta ruta?')">Eliminar</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>