<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Agencias Aduanas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>


<body class="container mt-4">

    <h2 class="text-center">Gestión de Agencias Aduanas</h2>

	<div class="mb-4 text-center">
  		<a href="mantenimiento.php" class="btn btn-outline-secondary">
    		← Volver a Mantenimiento
  		</a>
	</div>

	<!-- Mostrar mensaje de error de validación -->
  	<?php if(!empty($error)): ?>
    	<div class="alert alert-danger">
      		<?= htmlspecialchars($error) ?>
    	</div>
  	<?php endif; ?>
	
	<!-- Mensajes de éxito/error -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['msg']) ?>
        </div>
    <?php endif; ?>


	<!-- FORMULARIO DE AGREGAR / EDITAR -->
	<form method="post" action="editar_agencia_aduana.php" class="mb-5">
		<div class="mb-3 d-flex align-items-center">
			<label for="nombre" class="form-label me-2 mb-0" style="width: 200px;">Nombre</label>
			<input 
  				type="text" 
  				class="form-control w-50" 
  				id="nombre" 
  				name="nombre" 
  				value="<?= isset($registro['nombre']) ? htmlspecialchars($registro['nombre']) : '' ?>"
  				required
			>  
		</div>
		<div class="mb-3 d-flex align-items-center">
            <label for="ruc" class="form-label me-2 mb-0" style="width: 200px;">RUC</label>
            <input 
				type="text" 
				class="form-control w-50" 
				id="ruc" name="ruc" 
				value="<?= isset ($registro['ruc']) ? htmlspecialchars($registro['ruc']):'' ?>" 
				required>
        </div>
		<div class="mb-3 d-flex align-items-center">
            <label for="direccion" class="form-label me-2 mb-0" style="width: 200px;">Dirección</label>
            <input 
				type="text" 
				class="form-control w-50" 
				id="direccion" 
				name="direccion" 
				value="<?=isset($registro['direccion']) ? htmlspecialchars($registro['direccion']):'' ?>" 
				required>
        </div>

		<div class="mb-3 d-flex align-items-center">
  			<label for="departamento_id" class="form-label me-2 mb-0" style="width:200px;">
    			Departamento
  			</label>
  			<select id="departamento_id" name="departamento_id" class="form-select w-50" required>
    			<option value="">— Selecciona —</option>
    			<?php foreach($departamentos as $d): ?>
      			<option value="<?= $d['id'] ?>"
        			<?= $registro['departamento_id']==$d['id']?'selected':''?>>
        			<?= htmlspecialchars($d['nombre']) ?>
      			</option>
    			<?php endforeach; ?>
  			</select>
		</div>

		<!-- Provincia -->
		<div class="mb-3 d-flex align-items-center">
  			<label for="provincia_id" style="width:200px;" class="form-label me-2 mb-0">Provincia</label>
  			<select id="provincia_id" name="provincia_id" class="form-select w-50" required>
    			<option value="">— Selecciona antes un departamento —</option>
    			<!-- Se rellenará con JS -->
  			</select>
		</div>


    	<!-- Distrito -->
		<div class="mb-3 d-flex align-items-center">
  			<label for="distrito_id" style="width:200px;" class="form-label me-2 mb-0">Distrito</label>
  			<select id="distrito_id" name="distrito_id" class="form-select w-50" required>
    			<option value="">— Selecciona antes una provincia —</option>
    			<!-- Se rellenará con JS -->
  			</select>
	</div>
        
        
		      


		<button
      		type="submit"
      		name="<?= isset($registro['id']) && $registro['id']>0 ? 'actualizar' : 'agregar' ?>"
      		class="btn btn-primary"
    	>
      		<?= isset($registro['id']) && $registro['id']>0 ? 'Actualizar Agencia' : 'Agregar Agencia' ?>
    	</button>
	</form>
<hr>
<h2 class="h5 mb-3">Lista de Agencias Aduanas</h2>
<table class="table table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>Nombre</th>
            <th>RUC</th>
            <th>Dirección</th>
            <th>Distrito</th>
            <th>Provincia</th>
            <th>Departamento</th>
            <th>Estado</th>
            <th>Fecha Creación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($agencias as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['ruc']) ?></td>
                <td><?= htmlspecialchars($row['direccion']) ?></td>
                <td><?= htmlspecialchars($row['distrito_id']) ?></td>
                <td><?= htmlspecialchars($row['provincia_id']) ?></td>
                <td><?= htmlspecialchars($row['departamento_id']) ?></td>
                <td>
                    <?= $row['estado'] ? '<span class="badge bg-success">Activo</span>'
                                      : '<span class="badge bg-secondary">Eliminado</span>' ?>
                </td>
                <td><?= htmlspecialchars($row['fecha_creacion']) ?></td>
                <td>
                    <?php if($row['estado']): ?>
                        <a href="editar_agencia_aduana.php?editar=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✎</a>
                        <a href="editar_agencia_aduana.php?eliminar=<?= $row['id'] ?>" class="btn btn-sm btn-danger" 
                            onclick="return confirm('¿Eliminar esta agencia?')">🗑</a>
                    <?php else: ?>
                        <a href="editar_agencia_aduana.php?reactivar=<?= $row['id'] ?>" class="btn btn-sm btn-success">⟳</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
