<!-- vista_listado.php -->
<?php
	// Mostrar mensaje flash si existe
	$flash = getFlash();
?>

<!DOCTYPE html>
<html lang="es">
	<head>
    	<meta charset="UTF-8">
    	<title>Asignaciones de Conductores</title>
    	<meta name="viewport" content="width=device-width, initial-scale=1">

    	<!-- Bootstrap CSS -->
    	<link
    		href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    		rel="stylesheet"
    	>
    	<!-- DataTables CSS -->
    	<link
    		href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"
    		rel="stylesheet"
		>
    	<!-- CSS personalizado -->
    	<link 
			href="css/asignaciones.css" 
			rel="stylesheet"
		>

		<link
  			rel="stylesheet"
  			href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
		>

	</head>
	<body>
		<div class="container my-5">
    		<h2 class="mb-4 text-primary d-flex align-items-center">
    			<i class="fas fa-exchange-alt me-2"></i>
    			Asignaciones 
    			<small 
					class="text-muted ms-2">(Conductor - Tracto - Remolque)
				</small>
			</h2>

			<?php if (!empty($flash)): ?>
    			<div 
					class="alert alert-<?= sanitize($flash['type']) ?> alert-dismissible fade show" 
					role="alert">
        			<i class="fas <?= ($flash['type'] === 'success') ? 'fa-check-circle' : (($flash['type'] === 'danger') ? 'fa-exclamation-triangle' : 'fa-info-circle') ?> me-2"></i>
        			<?= sanitize($flash['msg']) ?>
        			<button 
						type="button" 
						class="btn-close" 
						data-bs-dismiss="alert" 
						aria-label="Close"></button>
    			</div>
			<?php endif; ?>


    	<div class="mb-3">
        	<a href="asignar_conductor.php" class="btn btn-primary me-2">
            	<i class="fas fa-plus"></i> Asignacion
        	</a>
        	<a href="/../../views/dashboard/erp_dashboard.php" class="btn btn-secondary">
            	<i class="fas fa-arrow-left"></i> Volver al Dashboard
        	</a>
    	</div>

    	<!-- Asignaciones Activas -->
		<h3 class="mt-5 mb-3 text-success d-flex align-items-center border-bottom pb-2 fw-bold">
    		<i class="fas fa-car-side me-2"></i>
    		Asignaciones Activas
		</h3>

    	<?php if ($asignacionesActivas->num_rows > 0): ?>
        	<div class="table-responsive">
            	<table id="tablaActivas" class="table table-bordered table-hover">
                	<!-- <thead class="table-primary text-center align-middle"> -->
					<thead class="table-dark text-center align-middle">
    					<tr>
        					<th data-bs-toggle="tooltip" data-bs-placement="top" title="Unidad tractora">
								<i class="fas fa-truck-moving me-2"></i> 
								<span class="fw-bold">Tracto</span>
							</th>
        					<th title="Remolque asignado">
								<i class="fas fa-trailer me-2"></i>
								<span class="fw-bold">Remolque</span>
							</th>
        					<th title="Nombre del conductor">
								<i class="fas fa-user me-1"></i>
								<span class="fw-bold">Conductor</span>
							</th>
        					<th title="Fecha de asignación">
								<i class="fas fa-calendar-day me-1"></i> 
								<span class="fw-bold">Fecha Asignacion</span>
							</th>
        					<th title="Estado de la asignación">
								<i class="fas fa-check-circle me-1"></i>
								<span class="fw-bold">Estado</span>
							</th>
        					<th title="Opciones disponibles">
								<i class="fas fa-tools me-1"></i>
								<span class="fw-bold">Acciones</span>
							</th>
    					</tr>
					</thead>

                	<tbody>
                		<?php while ($row = $asignacionesActivas->fetch_assoc()): ?>
                    	<tr>
                        		<td><?= sanitize($row['placa']) ?></td>
                        		<td><?= sanitize($row['modelo']) ?></td>
                        		<td><?= sanitize($row['conductor']) ?></td>
                        		<td><?= sanitize($row['fecha_inicio']) ?></td>
                        		<td><?= sanitize($row['estado']) ?></td>
                        		<td>
                            		<button
                            	  		class="btn btn-danger btn-sm btn-finalizar"
										data-bs-toggle="tooltip"
										data-bs-placement="top"
										title="Finalizar asignación"
										data-finalizar-id="<?= sanitize($row['id']) ?>"
									>
                            	    	<i class="fas fa-times-circle"></i> 
										Finalizar
                            		</button>
                        		</td>
                    		</tr>
                		<?php endwhile; ?>
                	</tbody>
            	</table>
        	</div>
    	<?php else: ?>
    		
			<div class="alert alert-warning d-flex justify-content-center align-items-center" role="alert">
    			<i class="fas fa-exclamation-circle me-2 fa-lg"></i>
    			<span class="fw-bold">No hay asignaciones activas.</span>
			</div>
		<?php endif; ?>



    	<!-- Historial de Asignaciones -->
		
		<h3 class="mt-5 mb-3 text-primary d-flex align-items-center border-bottom pb-2 fw-bold">
    		<i class="fas fa-history fa-lg me-2"></i>
    		Historial de Asignaciones
		</h3>


    	<?php if ($historialAsignaciones->num_rows > 0): ?>
        	<div class="table-responsive shadow-sm rounded">
            	<table id="tablaHistorial" class="table table-bordered table-hover align-middle">
        			<thead class="table-secondary text-center align-middle">
    					<tr>
        					<th data-bs-toggle="tooltip" data-bs-placement="top" title="Unidad tractora">
            					<i class="fas fa-truck-moving me-2"></i>
            					<span class="fw-bold">Tracto</span>
        					</th>
        					<th data-bs-toggle="tooltip" data-bs-placement="top" title="Remolque asignado">
            					<i class="fas fa-trailer me-2"></i>
            					<span class="fw-bold">Remolque</span>
        					</th>
        					<th data-bs-toggle="tooltip" data-bs-placement="top" title="Nombre del conductor">
            					<i class="fas fa-user-tie me-2"></i>
            					<span class="fw-bold">Conductor</span>
        					</th>
        					<th data-bs-toggle="tooltip" data-bs-placement="top" title="Fecha de inicio de asignación">
            					<i class="fas fa-calendar-day me-2"></i>
            					<span class="fw-bold">Fecha Asignación</span>
        					</th>
        					<th data-bs-toggle="tooltip" data-bs-placement="top" title="Fecha de finalización de asignación">
            					<i class="fas fa-calendar-check me-2"></i>
            					<span class="fw-bold">Fecha Fin</span>
        					</th>
        					<th data-bs-toggle="tooltip" data-bs-placement="top" title="Estado de la asignación">
            					<i class="fas fa-check-circle me-2"></i>
            					<span class="fw-bold">Estado</span>
        					</th>
    					</tr>
					</thead>

                	<tbody>
                		<?php while ($row = $historialAsignaciones->fetch_assoc()): ?>
                    		<tr>
                        		<td><?= sanitize($row['placa']) ?></td>
                        		<td><?= sanitize($row['modelo']) ?></td>
                        		<td><?= sanitize($row['conductor']) ?></td>
                        		<td><?= sanitize($row['fecha_inicio']) ?></td>
                        		<td><?= sanitize($row['fecha_fin']) ?></td>
                        		<td><?= sanitize($row['estado']) ?></td>
                    		</tr>
                		<?php endwhile; ?>
                	</tbody>
            	</table>
        	</div>
    	<?php else: ?>
    		<div class="alert alert-info d-flex justify-content-center align-items-center" role="alert">
    			<i class="fas fa-info-circle me-2 fa-lg"></i>
    			<span class="fw-bold">No hay historial de asignaciones.</span>
			</div>

		<?php endif; ?>


	</div>

	<?php include __DIR__ . '/vista_modal.php'; ?>

	<!-- JS: jQuery, DataTables, Bootstrap y script personalizado -->
	<script 
		src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script
	  	src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js">
	</script>
	<script
	  	src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
	</script>
	<script 
		src="js/asignaciones.js">
	</script>
	</body>
</html>
