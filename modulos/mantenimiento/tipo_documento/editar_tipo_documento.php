<?php
	// Habilitar visualización de errores (solo en desarrollo)
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	
	session_start();
	require_once __DIR__ . '/controllers/tipo_documento_controller.php';

	// sólo admin…
	if(!isset($_SESSION['usuario'])||$_SESSION['rol_nombre']!=='admin'){
		header('Location: ../sistema/login.php');
  		exit;
		}

	// ✅ INSERTA ESTO AQUÍ MISMO:
	if (isset($_GET['eliminar'])) {
    $id = (int) $_GET['eliminar'];
    $stmt = $conn->prepare("UPDATE tipos_documento SET estado = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: editar_tipo_documento.php?msg=Tipo+desactivado");
    exit;
}

if (isset($_GET['activar'])) {
    $id = (int) $_GET['activar'];
    $stmt = $conn->prepare("UPDATE tipos_documento SET estado = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: editar_tipo_documento.php?msg=Tipo+activado");
    exit;
}
	

	$msg = '';
	$error = '';
	if($_SERVER['REQUEST_METHOD']==='POST'){
  		$error = procesarTipoDocumento($_POST);
  		if($error===''){
    		header('Location: editar_tipo_documento.php?msg=Guardado');
    		exit;
  			}
		}

	$categorias = listarCategoriasDocumento();

	// $categorias = listarCategoriasDocumento();
	$tipos       = listarTiposDocumento();





?>

<!DOCTYPE html>
	<html lang="es">
	<head>
  		<meta charset="UTF-8">
  		<title>Tipos de Documento</title>
  		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body class="p-4">
  		<h1>Tipos de Documento</h1>

		<a href="../mantenimiento.php" class="btn btn-outline-secondary mb-4">
  			<i class="fas fa-arrow-left me-1"></i> Volver a Mantenimiento
		</a>
		
		<!-- Botón para abrir el modal -->
		<button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTipoDocumento">
  			➕ Nuevo Tipo de Documento
		</button>


  		<?php if($error): ?>
  		<div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
  			<?php elseif(isset($_GET['msg'])): ?>
  			<div class="alert alert-success"><?=htmlspecialchars($_GET['msg'])?></div>
  				<?php endif; ?>

  				<!-- Tabla -->
  				<table class="table table-striped">
    				<thead>
      					<tr>
        					<th>ID</th>
							<th>Categoría</th>
							<th>Nombre</th>
							<th>Descripción</th>
							<th>Estado</th>
							<th></th>
      					</tr>
    				</thead>
    				<tbody>
      					<?php foreach($tipos as $t): ?>
      					<tr>
        					<td><?=$t['id']?></td>
        					<td><?=htmlspecialchars($t['categoria'])?></td>
        					<td><?=htmlspecialchars($t['nombre'])?></td>
        					<td><?=htmlspecialchars($t['descripcion'])?></td>
        					<td><?=$t['estado']==1?'Activo':'Inactivo'?></td>
        					<td>
          						<a href="editar_tipo_documento.php?id=<?=$t['id']?>" 
								class="btn btn-sm btn-outline-primary">Editar</a>
          						<!-- opcional: botón Eliminar/Reactivar -->
							
  <?php if ($t['estado'] == 1): ?>
    <a href="editar_tipo_documento.php?eliminar=<?=$t['id']?>" 
       class="btn btn-sm btn-outline-danger"
       onclick="return confirm('¿Deseas desactivar este tipo de documento?')">
       Eliminar
    </a>
  <?php else: ?>
    <a href="editar_tipo_documento.php?activar=<?=$t['id']?>" 
       class="btn btn-sm btn-outline-success"
       onclick="return confirm('¿Deseas reactivar este tipo de documento?')">
       Activar
    </a>
  <?php endif; ?>
        					</td>
      					</tr>
      					<?php endforeach; ?>
    				</tbody>
  				</table>

  			<!-- Formulario -->
  			<?php
    			$registro = isset($_GET['id'])
       			? obtenerTipoDocumento($_GET['id'])
       			: ['id'=>0,'categoria_id'=>0,'nombre'=>'','descripcion'=>''];
  			?>
  			<!-- <form method="post" class="mt-4">
    			<input type="hidden" name="id" value="<?=$registro['id']?>">

    				<div class="mb-3">
      					<label>Categoría</label>
      					<select name="categoria_id" class="form-select" required>
        					<option value="">– Selecciona –</option>
        						<?php foreach($categorias as $c): ?>
        						<option value="<?=$c['id']?>" <?=$registro['categoria_id']==$c['id']?'selected':''?>>
          						<?=htmlspecialchars($c['nombre'])?>
        					</option>
        					<?php endforeach; ?>
      					</select>
    				</div>

    				<div class="mb-3">
      					<label>Nombre</label>
      					<input type="text" name="nombre" class="form-control"
             				value="<?=htmlspecialchars($registro['nombre'])?>" required>
    				</div>

    				<div class="mb-3">
      					<label>Descripción</label>
      					<textarea name="descripcion" class="form-control" rows="3"><?=htmlspecialchars($registro['descripcion'])?></textarea>
    				</div>

    			<button class="btn btn-primary">
      				<?=$registro['id']>0 ? 'Actualizar' : 'Agregar'?>
    			</button>
  			</form> -->



			<!-- Modal de Bootstrap -->
	<div class="modal fade" id="modalTipoDocumento" tabindex="-1" aria-labelledby="modalTipoDocumentoLabel" aria-hidden="true">
  		<div class="modal-dialog">
    		<form method="post" class="modal-content">
      			<div class="modal-header">
        			<h5 class="modal-title" id="modalTipoDocumentoLabel">
          				<?= $registro['id'] > 0 ? 'Editar Tipo de Documento' : 'Nuevo Tipo de Documento' ?>
        			</h5>
        			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      			</div>

				<!-- <pre><?php print_r($categorias); ?></pre> -->

      			<div class="modal-body">
        			<input type="hidden" name="id" value="<?= $registro['id'] ?>">
        				<div class="mb-3">
          					<label>Categoría</label>
          					<select name="categoria_id" class="form-select" required>
            					<option value="">– Selecciona –</option>
            					<?php foreach ($categorias as $c): ?>
              					<option value="<?= $c['id'] ?>" <?= $registro['categoria_id'] == $c['id'] ? 'selected' : '' ?>>
                					<?= $registro['categoria_id'] == $c['id'] ? 'selected' : '' ?>>
									<?= htmlspecialchars($c['nombre']) ?>
              					</option>
            					<?php endforeach; ?>
          					</select>
        				</div>

        				<div class="mb-3">
          					<label>Nombre</label>
          					<input type="text" name="nombre" class="form-control"
                 				value="<?= htmlspecialchars($registro['nombre']) ?>" required>
        				</div>

        				<div class="mb-3">
          					<label>Descripción</label>
          					<textarea name="descripcion" 
								class="form-control" 
								rows="3"><?= htmlspecialchars($registro['descripcion']) ?></textarea>
        				</div>
      			</div>
      			
				<div class="modal-footer">
        			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        			<button type="submit" class="btn btn-primary">Guardar</button>
      			</div>
    		</form>
  		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	

	<!-- Scripts de Bootstrap -->
  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  	<?php if (isset($_GET['id']) && $registro['id'] > 0): ?>
  	<script>
    	var modal = new bootstrap.Modal(document.getElementById('modalTipoDocumento'));
    	modal.show();
  	</script>
  	<?php endif; ?>

	</body>
</html>



