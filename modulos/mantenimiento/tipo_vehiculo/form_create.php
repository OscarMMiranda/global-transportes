<?php
	// 		/tipo_vehiculo/form_create.php
	// 		Debe venir de: TipoVehiculoController::create()
	// 		Variables disponibles:
	//   	$categorias  = listado de categorías preparado en el controller
	//   	$msg, $error = mensajes flash (opcional)

	include 'header.php';
?>

<div class="container py-4">
  <div class="card shadow-sm">
		<div class="card-header bg-success text-white">
      		<h4 class="mb-0">
        		<i class="fas fa-truck"></i> Registrar Nuevo Tipo de Vehículo
      		</h4>
    	</div>

	<div class="card-body">
		<?php if (!empty($_GET['error'])): ?>
			<div class="alert alert-danger">
				<?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
			</div>
		<?php endif; ?>

		<form method="post" action="index.php?action=store" class="needs-validation" novalidate>
			
			<!-- Nombre -->
			<div class="mb-3">
    			<label for="nombre" class="form-label">
      				<i class="fas fa-car"></i> 
						Nombre
    			</label>
    			<input 
      				type="text" 
      				name="nombre" 
      				id="nombre" 
      				class="form-control" 
      				required 
      				maxlength="100"
      				value="<?= isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8') : '' ?>"
    	 			accept=""
		 		>
  			</div>

			<!-- Categoría -->
			<div class="mb-3">
				<label for="categoria_id" class="form-label">
					<i class="fas fa-layer-group"></i>Categoría
				</label>
				<select name="categoria_id" id="categoria_id" class="form-select" required>
      				<option value="">-- Seleccionar --</option>
      				<?php foreach ($categorias as $cat): ?>
        			<option 
        				value="<?= (int)$cat['id'] ?>" 
        				<?= (isset($_POST['categoria_id']) && $_POST['categoria_id'] == $cat['id']) ? 'selected' : '' ?>
        			>
        				<?= htmlspecialchars($cat['nombre'], ENT_QUOTES, 'UTF-8') ?>
        			</option>
      				<?php endforeach; ?>
    			</select>
  			</div>
			
			<!-- Descripción -->
  			<div class="mb-3">
    			<label for="descripcion" class="form-label">
					<i class="fas fa-align-left"></i>Descripción
				</label>
    			<textarea 
      				name="descripcion" 
      				id="descripcion" 
      				class="form-control" 
      				rows="4" 
      				required
    			>
					<?= isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion'], ENT_QUOTES, 'UTF-8') : '' ?>
				</textarea>
  			</div>
			
			<!-- Botones -->
			<div class="d-flex justify-content-end gap-2">
    			<button type="submit" class="btn btn-success shadow-sm">
            		<i class="fas fa-plus-circle"></i> Agregar
          		</button>
          		<a href="index.php" class="btn btn-outline-secondary">
            		<i class="fas fa-times"></i> Cancelar
          		</a>
  			</div>
		</form>
</div>
</div>
</div>
<?php include 'footer.php'; ?>
