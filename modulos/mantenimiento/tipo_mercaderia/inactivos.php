<?php
	// inactivos.php
	ini_set('display_errors',1); error_reporting(E_ALL);
	session_start();

	// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../../includes/config.php';
// require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

	
	$resultado = $conn->query("
		SELECT id,nombre,descripcion 
    	FROM tipos_mercaderia 
   		WHERE estado = 0 
 		ORDER BY nombre
		");
?>

<!DOCTYPE html>
<html lang="es">
	<head><!-- tu head --></head>
	<body>
  		<div class="container py-4">
			<h1 class="h4">📦 Tipos de Mercadería Inactivos</h1>
			<a href="index.php" class="btn btn-sm btn-outline-primary mb-3">← Ver Activos</a>
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
        				<?php while($row = $resultado->fetch_assoc()): ?>
          				<tr class="text-muted">
            				<td><?= $row['id'] ?></td>
            				<td><?= htmlspecialchars($row['nombre']) ?></td>
            				<td><?= htmlspecialchars($row['descripcion']) ?></td>
            				<td>
								<!-- Restaurar -->
              					<form method="post" action="restaurar.php" class="d-inline">
            						<input type="hidden" name="id" value="<?= $row['id'] ?>">
            						<button class="btn btn-success btn-sm">Restaurar</button>
          						</form>
            				</td>
          				</tr>
        				<?php endwhile; ?>
        			</tbody>
      			</table>
    		</div>
  		</div>
	</body>
</html>
