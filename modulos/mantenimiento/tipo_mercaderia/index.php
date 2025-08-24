<?php
	// archivo	/mantenimiento/tipo_mercaderia/index.php

	session_start();

	// 1) Modo depuración (solo DEV)
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors',     1);
	ini_set('error_log',      __DIR__ . '/error_log.txt');

	// 2) Cargar configuración y conexión
	require_once __DIR__ . '/../../../includes/config.php';
	
	$conn = getConnection();

	// depuración:
	if (session_status() !== PHP_SESSION_ACTIVE) {
    	die("❌ La sesión NO arrancó correctamente.");
		}
	if (!isset($conn) || !($conn instanceof mysqli)) {
    	die("❌ La variable \$conn no existe o no es un mysqli.");
		}
	
	// Compatibilidad con PHP 5.6
	$msg   = isset($_SESSION['msg'])   ? $_SESSION['msg']   : null;
	$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;

	// Limpia los flashes
	unset($_SESSION['msg'], $_SESSION['error']);

	// Tu lógica continúa…
	$resultado = $conn->query("
 		SELECT id, nombre, descripcion, estado 
    	FROM tipos_mercaderia 
   		WHERE estado = 1 
		ORDER BY nombre
		");
?>

<!DOCTYPE html>
<html lang="es">
	<head>
    	<meta charset="UTF-8">
    	<title>Tipos de Mercadería</title>
    	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

	</head>

	<body class="bg-light">
    	<div class="container py-4">
			<!-- Cabecera con botón de retorno -->
		 	<div class="d-flex justify-content-between align-items-center mb-3">
        		<h1 class="h4 mb-4">
  					<i class="fas fa-box me-2"></i> 
					Tipos de Mercadería
				</h1>

				<a 
					href="../mantenimiento.php" 
					class="btn btn-outline-primary btn-sm">
  					<i class="fas fa-arrow-left"></i> 
					Volver al Mantenimiento
				</a>

			</div>

	  		<!-- Mensajes flash -->
        	<?php if ($msg): ?>
        	<div class="alert alert-success">Registro <?php echo $msg; ?> correctamente.</div>
        	<?php endif; ?>
        	<?php if ($error): ?>
        	<div class="alert alert-danger"><?php echo $error; ?></div>
        	<?php endif; ?>

			<!-- Botón para abrir el modal de agregar -->
    		<button 
				type="button" 
				class="btn btn-success mb-3" 
				data-bs-toggle="modal" 
				data-bs-target="#modalAgregar"
			>
  				<i class="fas fa-plus me-2"></i> 
				Agregar Nueva Mercadería
			</button>

			<!-- Nav tabs e integración de tablas -->
    	<ul class="nav nav-tabs mb-3" id="tabTipos" role="tablist">
      		<li class="nav-item">
        		<button 
					class="nav-link active" 
					data-bs-toggle="tab" 
					data-bs-target="#activos"
				>
  					<i class="bi bi-check-circle me-2"></i> 
					Activos
				</button>
      		</li>
      		<li class="nav-item">
        		<button 
					class="nav-link" 
					data-bs-toggle="tab" 
					data-bs-target="#inactivos"
				>
  					<i class="fas fa-ban me-2"></i> 
					Inactivos
				</button>
      		</li>
    	</ul>
    	
		<div class="tab-content">
      		<div class="tab-pane fade show active" id="activos">
        		<div class="table-responsive">
          			<table class="table table-striped">
            			<thead>
							<tr>
								<th>ID</th>
								<th>Nombre</th>
								<th>Descripción</th>
								<th>Acciones</th>
							</tr>
						</thead>
            			<tbody>
							<?php
              					$res = $conn->query("SELECT id,nombre,descripcion,estado 
									FROM tipos_mercaderia 
									WHERE estado=1 
									ORDER BY nombre");
              					while($r = $res->fetch_assoc()): 
							?>
              				<tr>
                				<td><?= $r['id'] ?></td>
                				<td><?= htmlspecialchars($r['nombre']) ?></td>
                				<td><?= htmlspecialchars($r['descripcion']) ?></td>
                				<td>
									<!-- Botón Editar (modal o página) -->
              						<button class="btn btn-warning btn-sm btn-editar" data-id="<?= $r['id'] ?>">
  										<i class="fas fa-edit"></i> 
										Editar
									</button>
									 <!-- Formulario Eliminar (soft delete) -->
              						<form method="post" action="eliminar.php" class="d-inline">
                						<input type="hidden" name="id" value="<?= $r['id'] ?>">
                						<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Deseas eliminar este registro?')">
  											<i class="fas fa-trash-alt"></i> 
											Eliminar
										</button>
              						</form>
								</td>
            				</tr>
              				<?php endwhile; ?>
            			</tbody>
          			</table>
        		</div>
      		</div>
	  		<div class="tab-pane fade" id="inactivos">
        		<div class="table-responsive">
          			<table class="table table-striped">
            			<thead>
							<tr>
								<th>ID</th>
								<th>Nombre</th>
								<th>Descripción</th>
								<th>Acciones</th>
							</tr>
						</thead>
            			<tbody>
              				<?php
              					$res2 = $conn->query(
								"SELECT id,nombre,descripcion,estado 
								FROM tipos_mercaderia 
								WHERE estado=0 
								ORDER BY nombre");
              					while($r2 = $res2->fetch_assoc()): 
							?>
              				<tr class="text-muted">
                				<td><?= $r2['id'] ?></td>
                				<td><?= htmlspecialchars($r2['nombre']) ?></td>
                				<td><?= htmlspecialchars($r2['descripcion']) ?></td>
                				<td>…acciones (p.ej. restaurar)…</td>
								<td>
              						<!-- Botón Restaurar -->
              						<form 
										method="post" 
										action="restaurar.php" 
										class="d-inline"
									>
                						<input 
											type="hidden" 
											name="id" 
											value="<?= $r2['id'] ?>"
										>
                						<button
                  							type="submit"
                  							class="btn btn-success btn-sm"
                  							onclick="return confirm('¿Restaurar este registro?')">
                  							Restaurar
                						</button>
              						</form>
            					</td>
             	 			</tr>
              				<?php endwhile; ?>
            			</tbody>
        			</table>
        		</div>
      		</div>
    	</div>
	</div>
		
		<!-- Modal: Agregar Nueva Mercadería -->
  		<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="tituloModalAgregar" aria-hidden="true">
    		<div class="modal-dialog">
      			<div class="modal-content">
        			<form method="post" action="procesar_formulario.php">
          				<div class="modal-header">
            				<h5 class="modal-title" id="tituloModalAgregar">Agregar Nueva Mercadería</h5>
            				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          				</div>
          				<div class="modal-body">
            				<div class="mb-3">
              					<label for="nombre" class="form-label">Nombre</label>
              					<input type="text" class="form-control" name="nombre" id="nombre" required>
            				</div>
           		 			<div class="mb-3">
            	  				<label for="descripcion" class="form-label">Descripción</label>
            	  				<textarea class="form-control" name="descripcion" id="descripcion" rows="3"></textarea>
            				</div>
          				</div>
          				<div class="modal-footer">
            				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            				<button type="submit" class="btn btn-success">Guardar</button>
          				</div>
        			</form>
      			</div>
    		</div>
  		</div>

		<!-- Modal para editar -->
		<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="tituloModalEditar" aria-hidden="true">
  			<div class="modal-dialog">
    			<div class="modal-content">
      				<!-- Aquí inyectaremos el form -->
      				<div id="contenidoModalEditar"></div>
    			</div>
  			</div>
		</div>


  		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

		<script>
			document.querySelectorAll('.btn-editar').forEach(btn => {
  				btn.addEventListener('click', () => {
    				const id = btn.getAttribute('data-id');
    				fetch(`editar.php?ajax=1&id=${id}`)
      				.then(res => res.text())
      				.then(html => {
        				document.getElementById('contenidoModalEditar').innerHTML = html;
        				// inicializar modal
        				const modal = new bootstrap.Modal(document.getElementById('modalEditar'));
        				modal.show();
      					})
      				.catch(console.error);
  					});
				});
		</script>


	</body>
</html>
