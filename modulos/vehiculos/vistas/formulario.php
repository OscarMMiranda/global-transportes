<?php
// formulario.php — Formulario para crear un nuevo vehículo
?>

<head>
	<meta charset="UTF-8">
	<title>Listado de Vehículos</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Bootstrap CSS -->
  	<link 
    	href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
    	rel="stylesheet">
  	<!-- FontAwesome -->
  	<link 
    	href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
    	rel="stylesheet">
  	<!-- DataTables CSS -->
  	<link 
    	rel="stylesheet" 
    	href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
</head>

<div class="container mt-4">
	<div class="card shadow-sm">
    	<div class="card-header bg-primary text-white d-flex align-items-center">
      		<i class="fas fa-truck me-2"></i>
      		<h5 class="mb-0">Registrar Nuevo Vehículo</h5>
    	</div>
    <div class="card-body">
      	<form action="index.php?action=store" method="POST" class="needs-validation" novalidate>
        	<div class="row g-3">
          		
			<!-- Placa -->
          		<div class="col-md-4">
            		<label for="placa" class="form-label">
              			<i class="fas fa-id-card-alt text-secondary me-1"></i> Placa
            		</label>
            		<input type="text" name="placa" id="placa" class="form-control" required>
          		</div>

          	<!-- Modelo -->
          		<div class="col-md-4">
            		<label for="modelo" class="form-label">
              			<i class="fas fa-car-side text-secondary me-1"></i> Modelo
            		</label>
            		<input type="text" name="modelo" id="modelo" class="form-control" required>
          		</div>

          	<!-- Año -->
          		<div class="col-md-4">
            		<label for="anio" class="form-label">
              			<i class="fas fa-calendar-alt text-secondary me-1"></i> Año
            		</label>
            		<input type="number" name="anio" id="anio" class="form-control" min="1990" max="2099" required>
          		</div>

			<!-- Tipo de Vehículo -->        
				<div class="col-md-4">
        			<label 
						for="tipo_id" 
						class="form-label">
						<i class="fas fa-car-side"></i> Tipo de Vehículo
					</label>
            		<select 
						name="tipo_id" 
						id="tipo_id" 
						class="form-select" 
						required>	
						<option value="" disabled <?= !$editando ? 'selected' : '' ?>>
              				Seleccione un tipo...
            			</option>
               			<?php
                 			// $sql = "SELECT id, nombre FROM tipo_vehiculo ORDER BY nombre ASC";
							$sql = "SELECT id, nombre FROM tipo_vehiculo WHERE fecha_borrado IS NULL ORDER BY nombre ASC";
                 			$result = $conn->query($sql);
                 				if ($result->num_rows > 0) 
									{
                     				while ($row = $result->fetch_assoc()) 
										{$selected = ($editando && $vehiculo['tipo_id'] == $row['id']) ? 'selected' : '';
            							echo "<option value='{$row['id']}' {$selected}>{$row['nombre']}</option>";
     									}
                 					} 
								else {
                     				echo "<option disabled>No hay tipos de vehículos registrados</option>";
                 				}
                		?>
            		</select>
          		</div>

          	<!-- Marca -->
          		<div class="col-md-4">
            		<label for="marca_id" class="form-label">
						<i class="fas fa-industry"></i> Marca
					</label>
            		<select name="marca_id" id="marca_id" class="form-select" required>
               			<!-- <option value="" selected disabled>Seleccione una marca...</option> -->
               			<option value="" disabled <?= !$editando ? 'selected' : '' ?>>
              				Seleccione una marca...
            			</option>
						<?php
                			$sql = "SELECT id, nombre FROM marca_vehiculo ORDER BY nombre ASC";
                			$result = $conn->query($sql);
                			if ($result->num_rows > 0) {
                   				while ($row = $result->fetch_assoc()) {
                       				echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                   					}
                				} 
							else {
                   				echo "<option disabled>No hay marcas registradas</option>";
                				}
                		?>
            		</select>
          		</div>

			<!-- Empresa -->
         		<div class="col-md-4">
            		<label 
						for="empresa_id" 
						class="form-label">
						<i class="fas fa-building"></i> Empresa
					</label>
            		<select 
						name="empresa_id" 
						id="empresa_id" 
						class="form-select" 
						required>
						<option 
							value="" disabled <?= !$editando ? 'selected' : '' ?>>
              				Seleccione una empresa...
            			</option>
               			<?php
                			$sql = 
								"SELECT id, razon_social 
								FROM empresa 
								ORDER BY razon_social ASC";
                			$result = $conn->query($sql);
                			if ($result->num_rows > 0) {
                   				while ($row = $result->fetch_assoc()) {
                       				echo "<option value='{$row['id']}'>{$row['razon_social']}</option>";
                   					}
                				} 
							else {
                   				echo "<option disabled>No hay empresas registradas</option>";
                				}
                		?>
            		</select>
         		</div>

          	<!-- Observaciones -->
          		<div class="col-md-12">
            		<label for="observaciones" class="form-label">
              			<i class="fas fa-comment-dots text-secondary me-1"></i> Observaciones
            		</label>
            		<textarea name="observaciones" id="observaciones" class="form-control" rows="3"></textarea>
          		</div>
        	</div>

        	<!-- Botones -->
        	<div class="mt-4 d-flex justify-content-between">		
          		<a href="index.php" class="btn btn-outline-secondary">
            		<i class="fas fa-arrow-left me-1"></i> Cancelar
          		</a>
          		<button type="submit" class="btn btn-primary">
            		<i class="fas fa-save me-1"></i> Guardar Vehículo
          		</button>
        	</div>
      	</form>
    </div>
  </div>
</div>

<!-- Validación Bootstrap -->
<script>
  	(function () {
    	'use strict';
    	window.addEventListener('load', function () {
      		var forms = document.getElementsByClassName('needs-validation');
      		Array.prototype.forEach.call(forms, function (form) {
        		form.addEventListener('submit', function (event) {
          			if (!form.checkValidity()) {
            			event.preventDefault();
            			event.stopPropagation();
          				}
          		form.classList.add('was-validated');
        	}, false);
      	});
    	}, false);
  	})();
</script>