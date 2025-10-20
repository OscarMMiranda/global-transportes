<?php
// archivo: /modulos/mantenimiento/agencia_aduana/modales/ver.php

require_once __DIR__ . '/../../../../includes/conexion.php';
require_once __DIR__ . '/../controllers/agencias_controller.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$registro = obtenerRegistro($id);

// Validación defensiva
if (!$registro || !is_array($registro)) {
    echo '<div class="alert alert-danger">❌ No se pudo cargar el registro.</div>';
    return;
}
?>

<table class="table table-bordered table-hover align-middle shadow-sm rounded-3">

<!-- <table class="table table-bordered table-sm"> -->
	<tbody>
      	<tr>
			<!-- <th class="bg-light text-end" style="width: 25%;">Nombre:</th> -->
        	<td><strong>Nombre:</strong></td>
      		<td><?= htmlspecialchars($registro['nombre']) ?></td>
    	</tr>
    	<tr>
      		<td><strong>RUC:</strong></td>
      		<td><?= htmlspecialchars($registro['ruc']) ?></td>
    	</tr>
    	<tr>
      		<td><strong>Dirección:</strong></td>
      		<td><?= htmlspecialchars($registro['direccion']) ?></td>
    	</tr>
 		<tr>
  			<td><strong>Departamento:</strong></td>
  			<td><?= htmlspecialchars($registro['departamento_nombre']) ?></td>
		</tr>
		<tr>
  			<td><strong>Provincia:</strong></td>
  			<td><?= htmlspecialchars($registro['provincia_nombre']) ?></td>
		</tr>
		<tr>
  			<td><strong>Distrito:</strong></td>
  			<td><?= htmlspecialchars($registro['distrito_nombre']) ?></td>
		</tr>
    	<tr>
      		<td><strong>Teléfono:</strong></td>
      		<td><?= htmlspecialchars($registro['telefono']) ?></td>
    	</tr>
    	<tr>
      		<td><strong>Correo:</strong></td>
      		<td><?= htmlspecialchars($registro['correo_general']) ?></td>
    	</tr>
    	<tr>
      		<td><strong>Contacto:</strong></td>
      		<td><?= htmlspecialchars($registro['contacto']) ?></td>
    	</tr>
    	<tr>
      		<td><strong>Estado:</strong></td>
      		<td>
        		<?= ($registro['estado'] == 1)
          		? '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Activo</span>'
          		: '<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Inactivo</span>' ?>
      		</td>
    	</tr>
    	<tr>
      		<td><strong>Fecha de creación:</strong></td>
      		<td><?= htmlspecialchars($registro['fecha_creacion']) ?></td>
    	</tr>
  	</tbody>
</table>