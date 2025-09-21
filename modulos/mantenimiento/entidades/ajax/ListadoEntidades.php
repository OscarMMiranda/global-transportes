<?php
	//	archivo	:	/modulos/mantenimiento/entidades/ajax/ListadoEntidades.php
	
if (!isset($conn) || !($conn instanceof mysqli)) {
    echo "<div class='alert alert-danger'>Error de conexión.</div>";
    return;
}

$estado = isset($estado) ? $estado : 'activo';

$sql = 
    "SELECT id, nombre, creado_en 
    FROM entidades 
    WHERE estado = '" . $conn->real_escape_string($estado) . "' ORDER BY creado_en DESC";
$res = $conn->query($sql);

if (!$res) {
    echo "<div class='alert alert-warning'>Error al consultar entidades.</div>";
    return;
}

if ($res->num_rows === 0) {
    echo "<div class='alert alert-info'>No hay entidades " . htmlspecialchars($estado) . " registradas.</div>";
    return;
}
?>

<div class="table-responsive">
  <table class="table table-bordered table-hover table-striped">
    <thead style="background-color: #337ab7; color: #fff;">
      <tr>
        <th style="width:60px;">ID</th>
        <th>Nombre</th>
        <th style="width:180px;">Fecha de creación</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $res->fetch_assoc()): ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['nombre']); ?></td>
          <td><?php echo date('d/m/Y H:i', strtotime($row['creado_en'])); ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>