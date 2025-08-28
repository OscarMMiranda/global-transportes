<?php 
	// 2) Modo depuración (solo DEV)
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors',     1);
	ini_set('error_log',      __DIR__ . '/error_log.txt');

	// 3) Cargar config.php (define getConnection() y rutas)
	require_once __DIR__ . '/../../includes/config.php';

	// 4) Obtener la conexión
	$conn = getConnection();

	$editando = false;
	$vehiculo = [];

	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    	$editando = true;
    	$id = (int) $_GET['id'];

    	$stmt = $conn->prepare("SELECT * FROM vehiculos WHERE id = ?");
    	$stmt->bind_param("i", $id);
    	$stmt->execute();
    	$res = $stmt->get_result();

    	if ($res && $res->num_rows === 1) {
        	$vehiculo = $res->fetch_assoc();
			} 
		else {
        	// Si el ID no existe, redirigir al listado
        	header("Location: vehiculos.php");
			exit;
			}
		$stmt->close();
		}
	require_once '../../includes/header_erp.php'; 
	require_once '../../includes/funciones.php'; 
?>

	<!-- Opcional: Generación del token CSRF -->



<script>
	document.addEventListener("DOMContentLoaded", function() {
  	// Solo inputs de texto y número, no selects
  	document.querySelectorAll("input[type='text'], input[type='number']")
    	.forEach(input => {
      		if (input.name !== "observaciones") {
        		input.addEventListener("input", e => {
          			e.target.value = e.target.value.toUpperCase();
        			});
      			}
    		});
		});
</script>


<div class="container py-4">
  	<div class="card shadow-sm">
    	<div class="card-header <?= $editando ? 'bg-warning' : 'bg-success' ?> text-white">
   			<h4 class="mb-0 text-center">
        		<i class="fas fa-truck-moving"></i>
        		<?= $editando ? 'Editar Vehículo' : 'Registrar Vehículo' ?>
      		</h4>
    	</div>
    	<div class="card-body">
    		<form	
				action="<?= $editando ? 'actualizar_vehiculos.php' : 'registrar_vehiculos.php' ?>" 
				method="POST" 
				id="form_vehiculos">
         	
				<!-- Si editamos, envío el ID -->
        		<?php if ($editando): ?>
          		<input 
            		type="hidden" 
            		name="id" 
            		value="<?= $vehiculo['id'] ?>"
          		>
        		<?php endif; ?>

				<!-- Placa -->
         		<div class="mb-3">
            		<label 
						for="placa" 
						class="form-label">
						<i class="fas fa-id-card"></i> Placa
					</label>
            		<input 
						type="text" 
						name="placa" 
						id="placa" 
						class="form-control" 
						placeholder="Ej. ABC123" 
						required
						 value="<?= $editando ? htmlspecialchars($vehiculo['placa']) : '' ?>"
						>
         		</div>

				<!-- Tipo de Vehículo -->
         		<div class="mb-3">
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
         		<div class="mb-3">
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
				
				<!-- Modelo -->
         		<div class="mb-3">
            		<label for="modelo" class="form-label">
						<i class="fas fa-car"></i> Modelo
					</label>
            		<input type="text" name="modelo" id="modelo" class="form-control" placeholder="Ej. XYZ" required>
         		</div>
				
				<!-- Año -->
         		<div class="mb-3">
            		<label for="anio" class="form-label">
						<i class="fas fa-calendar-alt"></i> Año
					</label>
            		<input 
						type="number" 
						name="anio" 
						id="anio" 
						class="form-control" 
						placeholder="Ej. 2022" 
						required
						value="<?= $editando 
                        	? htmlspecialchars($vehiculo['anio']) 
                        	: '' ?>"
					>
         		</div>
				
				<!-- Empresa -->
         		<div class="mb-3">
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
				
				<!-- Estado Operativo -->
         		<div class="mb-3">
            		<label for="estado_id" class="form-label">
						<i class="fas fa-traffic-light"></i> Estado del Vehículo
					</label>
            		<select name="estado_id" id="estado_id" class="form-select" required>
               			<option value="" disabled <?= !$editando ? 'selected' : '' ?>>
              				Seleccione un estado...
            			</option>
						
						<?php
                			$sql = 
								"SELECT id, nombre 
								FROM estado_vehiculo 
								ORDER BY nombre ASC";
                			$result = $conn->query($sql);
                			if ($result->num_rows > 0) {
                   				while ($row = $result->fetch_assoc()) {
                       				echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                   					}
                				} 
							else {
                   				echo "<option disabled>No hay estados registrados</option>";
                				}
                		?>
            		</select>
         		</div>
		
				<!-- Observaciones -->
        		<div class="mb-3">
            		<label for="observaciones" class="form-label">
						<i class="fas fa-comment-dots"></i> Observaciones
					</label>
					<textarea
            			name="observaciones"
            			id="observaciones"
            			class="form-control"
            			rows="3"
            			placeholder="Ingrese observaciones (opcional)"
          				><?= $editando 
                		? htmlspecialchars($vehiculo['observaciones']) 
                		: '' ?>
					</textarea>
				</div>

        		<div class="d-grid gap-2">
          			<button 
						type="submit" 
						class="btn btn-lg <?= $editando ? 'btn-warning' : 'btn-success' ?>">
            			<i class="fas fa-save"></i> <?= $editando ? 'Actualizar Vehículo' : 'Registrar Vehículo' ?>
          			</button>
        		</div>
      		</form>
    	</div>
  	</div>
  
  	<div class="mt-3 text-center">
    	<a href="vehiculos.php" class="btn btn-outline-secondary">
      		<i class="fas fa-arrow-left"></i> Volver al listado
    	</a>
  	</div>
</div>

<?php require_once '../../includes/footer_erp.php'; ?>
