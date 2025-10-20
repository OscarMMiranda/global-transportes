<?php
	// archivo: /modulos/mantenimiento/tipo_mercaderia/componentes/tabla_activos.php

	if (!isset($conn) || !($conn instanceof mysqli)) {
  		echo "<div class='alert alert-danger'>❌ Error de conexión.</div>";
  		return;
		}

	$res = $conn->query(
		"SELECT id, nombre, descripcion 
  				FROM tipos_mercaderia 
  				WHERE estado = 1 
  				ORDER BY nombre
				");

	if (!$res || $res->num_rows === 0) {
  		echo "<div class='alert alert-warning'>No hay registros activos.</div>";
  		return;
		}
?>

<div class="table-responsive">
  	<table id="tablaActivos" class="table table-striped table-hover table-sm align-middle tabla-mercaderia">
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
      			<tr>
        			<td><?= $r['id'] ?></td>
        			<td><?= htmlspecialchars($r['nombre']) ?></td>
        			<td><?= htmlspecialchars($r['descripcion']) ?></td>
        			<td class="text-center">
          				<button class="btn btn-warning btn-sm btn-editar" data-id="<?= $r['id'] ?>">
            				<i class="fas fa-edit"></i> Editar
          				</button>
          				<form method="post" action="ajax/eliminar.php" class="d-inline">
            				<input type="hidden" name="id" value="<?= $r['id'] ?>">
            				<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este registro?')">
            	  				<i class="fas fa-trash-alt"></i> Eliminar
            				</button>
          				</form>
        			</td>
      			</tr>
      		<?php endwhile; ?>
    	</tbody>
  	</table>
</div>