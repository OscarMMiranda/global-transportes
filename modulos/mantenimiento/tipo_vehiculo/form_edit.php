<?php
// form_edit.php

// Validación de existencia del registro
if (!isset($registro) || !is_array($registro)) {
    echo "<p>Error: No se encontró el registro a editar.</p>";
    return;
}

// Capturar mensaje de error (nombre duplicado, etc.)
$error = isset($_GET['error'])
    ? htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8')
    : '';

// Preservar valores: si viene de POST (hubo error), los usamos; si no, los originales
$oldNombre      = isset($_POST['nombre'])       ? trim($_POST['nombre'])            : $registro['nombre'];
$oldDescripcion = isset($_POST['descripcion'])  ? trim($_POST['descripcion'])       : $registro['descripcion'];
$oldCategoriaId = isset($_POST['categoria_id']) ? (int)$_POST['categoria_id']       : (int)$registro['categoria_id'];
?>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


<?php if ($error): ?>
	<div class="alert alert-danger">
    	<?= $error ?>
  	</div>
<?php endif; ?>

<div class="container py-4">

		<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">
        <i class="fas fa-edit"></i> Editar Tipo de Vehículo
      </h4>
    </div>

	<div class="card-body">
		<?php if ($error): ?>
        	<div class="alert alert-danger alert-dismissible fade show" role="alert">
          		<i class="fas fa-exclamation-triangle"></i> <?= $error ?>
          		<button type="button" class="btn-close" data-bs-dismiss="alert">
				</button>
        	</div>
      	<?php endif; ?>

	<form method="post" action="index.php?action=update&id=<?= (int)$registro['id'] ?>">
		
		<!-- Nombre -->
		<div class="mb-3">
    		<label for="nombre" class="form-label">
            	<i class="fas fa-car"></i> Nombre
          	</label>
    		<input
      			type="text"
      			class="form-control"
      			name="nombre"
      			id="nombre"
      			value="<?= htmlspecialchars($oldNombre, ENT_QUOTES, 'UTF-8') ?>"
      			required
      			maxlength="100"
    		>
  		</div>

		<!-- Categoría -->
		<div class="mb-3">
          	<label for="categoria_id" class="form-label">
            	<i class="fas fa-layer-group"></i> Categoría
          	</label>
          	<select class="form-select" name="categoria_id" id="categoria_id" required>
            	<option value="">[Seleccionar]</option>
            	<?php foreach ($categorias as $cat): ?>
            	<option 
					value="<?= (int)$cat['id'] ?>"
            	    <?= $oldCategoriaId === (int)$cat['id'] ? 'selected' : '' ?>
            	>
            	    <?= htmlspecialchars($cat['nombre'], ENT_QUOTES, 'UTF-8') ?>
            	</option>
            	<?php endforeach; ?>
          	</select>
        </div>

		<!-- Descripción -->
    	<div class="mb-3">
          	<label for="descripcion" class="form-label">
            	<i class="fas fa-align-left"></i> Descripción
          	</label>
          	<textarea
            	class="form-control"
            	name="descripcion"
            	id="descripcion"
            	rows="4"
            	required
          	>
			<?= htmlspecialchars($oldDescripcion, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

		<!-- Última modificación -->
    	<div class="mb-3">
          	<label class="form-label"><i class="fas fa-clock"></i> Última modificación</label>
          	<div class="form-control-plaintext">
            	<?= !empty($registro['fecha_modificacion'])
                ? htmlspecialchars($registro['fecha_modificacion'], ENT_QUOTES, 'UTF-8')
                : '<span class="text-danger">[Sin fecha registrada]</span>' ?>
          	</div>
        </div>

		<!-- Botones -->
  		<div class="d-flex justify-content-end gap-2">
        	<button type="submit" name="actualizar" class="btn btn-success shadow-sm">
        		<i class="fas fa-save"></i> Guardar cambios
          	</button>
          	<a href="index.php" class="btn btn-outline-secondary">
            	<i class="fas fa-times"></i> Cancelar
          	</a>
        </div>
	</form>

	</div>
</div>
