<?php 
	require_once '../../includes/conexion.php'; 
	require_once '../../includes/header_erp.php'; 
	require_once '../../includes/funciones.php'; 
?>

	<!-- Opcional: Generación del token CSRF -->
<?php 
	// if (!isset($_SESSION['csrf_token'])) {
	//     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	// } 

	// 1) Generar token CSRF si no existe
	// if (!isset($_SESSION['csrf_token'])) {
    // 	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	// 	}

	// 2) Si vienen por GET?id=… cargamos datos para edición
	// $editando = false;
	// if (isset($_GET['id'])) {
    // 	$editando = true;
    // 	$idVeh = (int) $_GET['id'];
    // 	$stmt = $conn->prepare("SELECT * FROM vehiculos WHERE id = ?");
    // 	$stmt->bind_param("i", $idVeh);
    // 	$stmt->execute();
    // 	$vehiculo = $stmt->get_result()->fetch_assoc() ?: null;
    // 	$stmt->close();
    // 	if (!$vehiculo) {
    //     	$_SESSION['error'] = "Vehículo no encontrado";
    //     	header("Location: vehiculos.php");
    //     	exit;
    // 		}
	// 	}

?>


<!-- <script>
	document.addEventListener("DOMContentLoaded", function () {
    // Convertir a mayúsculas todos los inputs de texto, número y selects (excepto observaciones)
    const inputs = document.querySelectorAll("input[type='text'], input[type='number'], select");
    inputs.forEach(input => {
    	if (input.name !== "observaciones") 
			{
        	input.addEventListener("input", function () 
				{
                this.value = this.value.toUpperCase();
            	});
        	}
    	});
	});
</script> -->


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


<div class="container mt-4">
  	<div class="card shadow-sm">
    	<div class="card-header bg-primary text-white">
      		<h3 class="card-title text-center mb-0">
				<?= $editando ? "Editar Vehículo" : "Registrar Vehículo" ?>
			</h3>
    	</div>
    	<div class="card-body">
    		<form	
				action="<?= $editando ? 'actualizar_vehiculos.php' : 'registrar_vehiculos.php' ?>" 
				method="POST" 
				id="form_vehiculos">
         	<!-- (Opcional) Campo oculto para CSRF -->
         	<!-- <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>"> -->

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
						Placa:
					</label>
            		<input 
						type="text" 
						name="placa" 
						id="placa" 
						class="form-control" 
						placeholder="Ej. ABC123" 
						required>
         		</div>

				<!-- Tipo -->
         		<div class="mb-3">
            		<label 
						for="tipo_id" 
						class="form-label">
						Tipo de Vehículo:
					</label>
            		<select 
						name="tipo_id" 
						id="tipo_id" 
						class="form-select" 
						required>
               			<!-- <option value="" selected disabled>Seleccione un tipo...</option> -->
						<option value="" disabled <?= !$editando ? 'selected' : '' ?>>
              				Seleccione un tipo...
            			</option>
               			<?php
                 			$sql = "SELECT id, nombre FROM tipo_vehiculo ORDER BY nombre ASC";
                 			$result = $conn->query($sql);
                 			if ($result->num_rows > 0) 
								{
                     			while ($row = $result->fetch_assoc()) 
									{
                         			echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
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
            		<label for="marca_id" class="form-label">Marca:</label>
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
            		<label for="modelo" class="form-label">Modelo:</label>
            		<input type="text" name="modelo" id="modelo" class="form-control" placeholder="Ej. XYZ" required>
         		</div>
				
				<!-- Año -->
         		<div class="mb-3">
            		<label for="anio" class="form-label">Año:</label>
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
						Empresa:
					</label>
            		<select 
						name="empresa_id" 
						id="empresa_id" 
						class="form-select" 
						required>
               			<!-- <option 
							value="" selected disabled>
							Seleccione una empresa...
						</option> -->

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
            		<label for="estado_id" class="form-label">Estado del Vehículo:</label>
            		<select name="estado_id" id="estado_id" class="form-select" required>
               			<!-- <option value="" selected disabled>Seleccione un estado...</option> -->
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
            <label for="observaciones" class="form-label">Observaciones:</label>
            <!-- <textarea name="observaciones" id="observaciones" class="form-control" rows="3" placeholder="Ingrese observaciones (opcional)"></textarea> -->
         
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
				class="btn btn-success btn-lg">
				<?= $editando ? 'Actualizar Vehículo' : 'Registrar Vehículo' ?>
			</button>
        </div>
      </form>
    </div>
  </div>
  
  <div class="mt-3 text-center">
      <a href="vehiculos.php" class="btn btn-secondary">← Volver a Vehículos</a>
  </div>
</div>

<?php require_once '../../includes/footer_erp.php'; ?>
