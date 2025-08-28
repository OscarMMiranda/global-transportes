<?php
// formulario_editar.php — Formulario para editar un vehículo existente
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
    	<div class="card-header bg-warning text-dark d-flex align-items-center">
      		<i class="fas fa-edit me-2"></i>
      		<h5 class="mb-0">Editar Vehículo</h5>
    	</div>

		<div class="card-body">
      		<form action="index.php?action=update&id=<?= $vehiculo['id'] ?>" method="POST" class="needs-validation" novalidate>
        		<div class="row g-3">
          	
					<!-- Placa -->
          			<div class="col-md-4">
            			<label for="placa" class="form-label">
            	  			<i class="fas fa-id-card-alt text-secondary me-1"></i> Placa
            			</label>
            			<input type="text" name="placa" id="placa" value="<?= htmlspecialchars($vehiculo['placa']) ?>" class="form-control" required>
          			</div>

          			<!-- Modelo -->
          			<div class="col-md-4">
            			<label for="modelo" class="form-label">
            	  			<i class="fas fa-car-side text-secondary me-1"></i> Modelo
            			</label>
            			<input type="text" name="modelo" id="modelo" value="<?= htmlspecialchars($vehiculo['modelo']) ?>" class="form-control" required>
          			</div>

          			<!-- Año -->
          			<div class="col-md-4">
            			<label for="anio" class="form-label">
            	  			<i class="fas fa-calendar-alt text-secondary me-1"></i> Año
            			</label>
            			<input type="number" name="anio" id="anio" value="<?= intval($vehiculo['anio']) ?>" class="form-control" min="1990" max="2099" required>
          			</div>

          			<!-- Tipo -->
          			<div class="col-md-4">
            			<label for="tipo_id" class="form-label">
            	  			<i class="fas fa-truck-moving text-secondary me-1"></i> Tipo de Vehículo
            			</label>
            			<select name="tipo_id" class="form-select">
                    <option value="" disabled>Seleccione un tipo...</option>
                    <?php
                    $sqlTipos = "SELECT id, nombre FROM tipo_vehiculo WHERE fecha_borrado IS NULL ORDER BY nombre ASC";
                    $resultTipos = $conn->query($sqlTipos);
                    while ($tipo = $resultTipos->fetch_assoc()) {
                        $selected = ($tipo['id'] == $vehiculo['tipo_id']) ? "selected" : "";
                        echo "<option value='{$tipo['id']}' {$selected}>{$tipo['nombre']}</option>";
                    }
                    ?>
                </select>
          			</div>

          			<!-- Marca -->
          			<div class="col-md-4">
            			<label for="marca_id" class="form-label">
            	  			<i class="fas fa-industry text-secondary me-1"></i> Marca
            			</label>
            			<select name="marca_id" class="form-select">
                    		<option value="" disabled>Seleccione una marca...</option>
                    		<?php
                    			$sqlMarcas = "SELECT id, nombre FROM marca_vehiculo ORDER BY nombre ASC";
                    			$resultMarcas = $conn->query($sqlMarcas);
                    			while ($marca = $resultMarcas->fetch_assoc()) {
                        			$selected = ($marca['id'] == $vehiculo['marca_id']) ? "selected" : "";
                        			echo "<option value='{$marca['id']}' {$selected}>{$marca['nombre']}</option>";
                    			}
                    		?>
                		</select>
          			</div>

          			<!-- Empresa -->
          			<div class="col-md-4">
            			<label for="empresa_id" class="form-label">
            	  			<i class="fas fa-building text-secondary me-1"></i> Empresa
            			</label>
            			<select name="empresa_id" class="form-select">
                    		<option value="" disabled>Seleccione una empresa...</option>
                    		<?php
                    			$sqlEmpresas = "SELECT id, razon_social FROM empresa ORDER BY razon_social ASC";
                    			$resultEmpresas = $conn->query($sqlEmpresas);
                    			while ($empresa = $resultEmpresas->fetch_assoc()) {
                        			$selected = ($empresa['id'] == $vehiculo['empresa_id']) ? "selected" : "";
                        			echo "<option value='{$empresa['id']}' {$selected}>{$empresa['razon_social']}</option>";
                    				}
                    		?>
                		</select>
          			</div>

          			<!-- Observaciones -->
          			<div class="col-md-12">
            			<label for="observaciones" class="form-label">
            	  			<i class="fas fa-comment-dots text-secondary me-1"></i> Observaciones
            			</label>
            			<textarea name="observaciones" id="observaciones" class="form-control" rows="3"><?= htmlspecialchars($vehiculo['observaciones']) ?></textarea>
          			</div>
        		</div>

        		<!-- Botones -->
        		<div class="mt-4 d-flex justify-content-between">
          			<a href="index.php" class="btn btn-outline-secondary">
            			<i class="fas fa-arrow-left me-1"></i> Cancelar
          			</a>
          			<button type="submit" class="btn btn-warning">
            			<i class="fas fa-save me-1"></i> Actualizar Vehículo
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