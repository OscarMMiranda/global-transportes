<?php
	// archivo: /modulos/mantenimiento/tipo_vehiculo/vista/lista_activos.php
?>

<?php if (!empty($datos)): ?>
  	<h5 class="mt-1 mb-2 text-success">
    	<i class="fas fa-check-circle me-1"></i> Vehículos Activos
  	</h5>

  	<table id="tablaActivos" class="table table-striped table-hover">
    	<thead>
      		<tr>
        		<th>ID</th>
        		<th>Nombre</th>
        		<th>Categoría</th>
        		<th>Descripción</th>
        		<th>Última modificación</th>
        		<th>Acciones</th>
      		</tr>
    	</thead>
    	<tbody>
      		<?php foreach ($datos as $tipo): ?>
        		<tr>
          			<td><?= (int) $tipo['id'] ?></td>
          			<td><?= htmlspecialchars($tipo['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
          			<td><?= htmlspecialchars($tipo['categoria_nombre'], ENT_QUOTES, 'UTF-8') ?></td>
          			<td><?= htmlspecialchars($tipo['descripcion'], ENT_QUOTES, 'UTF-8') ?></td>
          			<td><?= htmlspecialchars($tipo['fecha_modificacion'], ENT_QUOTES, 'UTF-8') ?></td>
		  			<td class="text-center">
  						<!-- Ver -->
  						<button class="btn btn-sm btn-info btn-ver me-1" data-id="<?= $tipo['id'] ?>" title="Ver detalles">
    						<i class="fas fa-eye"></i>
  						</button>

  						<!-- Editar -->
  						<button class="btn btn-sm btn-primary btn-editar me-1" data-id="<?= $tipo['id'] ?>" title="Editar">
    						<i class="fas fa-edit"></i>
  						</button>

  						<!-- Borrar -->
  						<button class="btn btn-sm btn-danger btn-borrar" data-id="<?= $tipo['id'] ?>" title="Eliminar">
    						<i class="fas fa-trash-alt"></i>
  						</button>
					</td>
        		</tr>
      		<?php endforeach; ?>
    	</tbody>
  	</table>
<?php else: ?>
  	<div class="alert alert-info text-center shadow-sm py-3 rounded-3">
    	<i class="fas fa-info-circle me-2"></i> 
    	No hay vehículos activos registrados.
	</div>
<?php endif; ?>