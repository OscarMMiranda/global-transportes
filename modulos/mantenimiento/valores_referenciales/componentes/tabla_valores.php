<?php
// archivo: componentes/tabla_valores.php

if (!isset($valores) || !is_array($valores)) {
    echo '<div class="alert alert-warning">⚠️ No se pudo cargar la tabla de valores referenciales.</div>';
    return;
}

if (empty($valores)) {
    echo '<div class="alert alert-info">ℹ️ No hay valores referenciales registrados.</div>';
    return;
}
?>

<table class="table table-bordered table-striped">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>Zona</th>
      <th>Tipo de Mercadería</th>
      <th>Año</th>
      <th>Monto</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($valores as $i => $v): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><?= htmlspecialchars($v['zona']) ?></td>
        <td><?= htmlspecialchars($v['tipo_mercaderia']) ?></td>
        <td><?= (int)$v['anio'] ?></td>
        <td>S/ <?= number_format($v['monto'], 2) ?></td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>