<?php
// archivo: /modulos/mantenimiento/tipo_mercaderia/componentes/tabla_inactivos.php

if (!isset($conn) || !($conn instanceof mysqli)) {
  echo "<div class='alert alert-danger'>❌ Error de conexión.</div>";
  return;
}

$res = $conn->query("
  SELECT id, nombre, descripcion 
  FROM tipos_mercaderia 
  WHERE estado = 0 
  ORDER BY nombre
");

if (!$res || $res->num_rows === 0) {
  echo "<div class='alert alert-warning'>No hay registros inactivos.</div>";
  return;
}
?>

<div class="table-responsive">
  <table id="tablaInactivos" class="table table-striped table-hover table-sm align-middle tabla-mercaderia">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th class="text-center">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($r = $res->fetch_assoc()): ?>
      <tr class="text-muted">
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['nombre']) ?></td>
        <td><?= htmlspecialchars($r['descripcion']) ?></td>
        <td class="text-center">
          <form method="post" action="ajax/restaurar.php" class="d-inline">
            <input type="hidden" name="id" value="<?= $r['id'] ?>">
            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('¿Restaurar este registro?')">
              <i class="fas fa-undo-alt"></i> Restaurar
            </button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>