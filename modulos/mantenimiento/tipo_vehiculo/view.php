<?php
	// archivo: /modulos/mantenimiento/tipo_vehiculo/view.php

	// 1) Mostrar errores en desarrollo
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require_once 'helpers.php';

	// 2) Validar sesión
	if (session_status() === PHP_SESSION_NONE) {
	    session_start();
		}

	if (empty($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
		header('Location: ../../../login.php');
		exit;
		}

	// 3) Mensajes
	if (!empty($msg)) {
		echo "<div class='alert alert-success'>{$msg}</div>";
		}
	if (!empty($error)) {
		echo "<div class='alert alert-danger'>{$error}</div>";
		}

	$titulo = 'Gestión de Tipos de Vehículo';
  	// include 'header.php';
?>

<!DOCTYPE html>
<html lang="es">
	<head>
    	<meta charset="UTF-8">
    	<title><?= $titulo ?></title>

    	<!-- Bootstrap CSS -->
    	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    	<!-- FontAwesome desde CDN -->
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
      	crossorigin="anonymous" referrerpolicy="no-referrer" />

    	<!-- Bootstrap JS (con Popper incluido) -->
    	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	</head>
	
	<body>
    	<h2 class="mb-4">
    		<i class="fas fa-truck"></i> 
			Gestión de Tipos de Vehículo
		</h2>

		<!-- Botones de acción -->
		<div class="d-flex gap-2 mb-4">
    		<!-- Botón agregar -->
    		<a href="index.php?action=create" class="btn btn-success">
        		<i class="fas fa-plus"></i> Agregar nuevo tipo
    		</a>

    	<!-- Botón volver -->
    		<a href="../mantenimiento.php" class="btn btn-secondary">
        		<i class="fas fa-arrow-left"></i> Volver al módulo principal
    		</a>
		</div>
	
		<!-- 🔹 Tabla de tipos activos -->
		<h4>
			<i class="fas fa-check-circle text-success"></i> 
			Tipos activos
		</h4>
		<table class="table table-striped table-hover">
        	<thead class="table-light">
    			<tr>
        			<th><i class="fas fa-hashtag"></i> ID</th>
        			<th><i class="fas fa-car"></i> Nombre</th>
        			<th><i class="fas fa-align-left"></i> Descripción</th>
        			<th><i class="fas fa-layer-group"></i> Categoría</th>
        			<th><i class="fas fa-clock"></i> Última modificación</th>
        			<th><i class="fas fa-cogs"></i> Acciones</th>
    			</tr>
			</thead>

        	<tbody>
            	<?php if (!empty($tipos_activos) && is_array($tipos_activos)): ?>
					<?php foreach ($tipos_activos as $tipo): ?>
                    	<tr>
                    	    <td><?= htmlspecialchars($tipo['id']) ?></td>
                    	    <td><?= htmlspecialchars($tipo['nombre']) ?></td>
                    	    <td><?= htmlspecialchars($tipo['descripcion']) ?></td>
                    	   
							<td>
    							<?= isset($tipo['categoria']) && $tipo['categoria'] !== ''
        							? htmlspecialchars($tipo['categoria'], ENT_QUOTES, 'UTF-8')
        							: '<span style="color:orange">[Sin categoría]</span>' 
								?>
							</td>
							
							<td>
	    						<?php
        							if (!empty($tipo['fecha_actualizacion'])) {
            							echo htmlspecialchars($tipo['fecha_actualizacion'], ENT_QUOTES, 'UTF-8');
        								} 
									elseif (!empty($tipo['fecha_creado'])) {
            							echo htmlspecialchars($tipo['fecha_creado'], ENT_QUOTES, 'UTF-8');
        								} 
									else {
            							echo '<span style="color:red">[Sin fecha registrada]</span>';
        								}
    							?>
								
							</td>

                    	    <!-- <td>
								<div class="d-flex gap-2">
                    	        	<a href="index.php?action=edit&id=<?= $tipo['id'] ?>">✏️ Editar</a> |
                    	        	<a href="index.php?action=delete&id=<?= $tipo['id'] ?>"
                    	          		onclick="return confirm('¿Estás seguro de eliminar este tipo?');">🗑️ Eliminar</a>
								</div>
							</td> -->

							<td>
  								<div class="d-flex gap-2">
    								<a href="index.php?action=edit&id=<?= $tipo['id'] ?>" class="btn btn-warning btn-sm">
      									<i class="fas fa-edit"></i>
										Editar
    								</a>
    								<a href="index.php?action=delete&id=<?= $tipo['id'] ?>" 
   										class="btn btn-danger"
   										onclick="return confirm('¿Seguro que quieres eliminar este tipo?')"
									>
  										<i class="fas fa-trash"></i> Eliminar
									</a>
  								</div>
							</td>

                    	</tr>
                	<?php endforeach; ?>
            		<?php else: ?>
                		<tr><td colspan="5">No se encontraron tipos de vehículo.</td></tr>
            	<?php endif; ?>
        	</tbody>
    	</table>

		<h4 class="mt-5">
    <i class="fas fa-trash text-danger"></i> Tipos eliminados
</h4>
<table class="table table-bordered table-sm">
    <thead class="table-danger">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Fecha eliminación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($tipos_eliminados)): ?>
            <?php foreach ($tipos_eliminados as $tipo): ?>
                <tr>
                    <td><?= htmlspecialchars($tipo['id']) ?></td>
                    <td><?= htmlspecialchars($tipo['nombre']) ?></td>
                    <td><?= htmlspecialchars($tipo['descripcion']) ?></td>
                    <td><?= htmlspecialchars($tipo['categoria']) ?></td>
                    <td><?= htmlspecialchars($tipo['fecha_borrado']) ?></td>
                    <td>
                        <form method="post" action="index.php?action=reactivar">
                            <input type="hidden" name="id" value="<?= $tipo['id'] ?>">
                            <button class="btn btn-outline-success btn-sm">
                                <i class="fas fa-undo"></i> Reactivar
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">No hay tipos eliminados.</td></tr>
        <?php endif; ?>
    </tbody>
</table>


	<?php include 'footer.php'; ?>

  

