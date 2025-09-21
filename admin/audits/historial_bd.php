<?php
	// /admin/users/historial_bd.php
	
	session_start();

	// 01.	Modo depuración (solo DEV)
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors',     1);
	ini_set('error_log',      __DIR__ . '/error_log.txt');

	// 02.	Cargar config.php (define getConnection() y rutas)
	require_once __DIR__ . '/../../includes/config.php';

	// 03.	Obtener la conexión
	$conn = getConnection();

	require_once __DIR__ . '/../../includes/helpers.php';

	// 04.	Verificar acceso solo para administradores
	if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    	die("❌ Acceso denegado.");
		}

	// 05. 	Verificar existencia de tabla
	$sql_verificar = "SHOW TABLES LIKE 'historial_bd'";
	$resultado_verificar = $conn->query($sql_verificar);

	if (!$resultado_verificar || $resultado_verificar->num_rows == 0) {
    	die("❌ Error: La tabla 'historial_bd' no existe.");
		}

	// 04. 	Leer filtros
	$filtro_usuario = isset($_GET['usuario']) ? $conn->real_escape_string($_GET['usuario']) : '';
	$filtro_tabla   = isset($_GET['tabla'])   ? $conn->real_escape_string($_GET['tabla'])   : '';
	$filtro_fecha   = isset($_GET['fecha'])   ? $conn->real_escape_string($_GET['fecha'])   : '';
	$filtro_accion  = isset($_GET['accion'])  ? $conn->real_escape_string($_GET['accion'])  : '';


	// Sanitizar entrada (recomendación: reemplazar por prepared statements en etapa avanzada)
	$filtro_usuario = $conn->real_escape_string($filtro_usuario);
	$filtro_tabla   = $conn->real_escape_string($filtro_tabla);
	$filtro_fecha   = $conn->real_escape_string($filtro_fecha);
	$filtro_accion  = $conn->real_escape_string($filtro_accion);

	// 05. 	Construir cláusula WHERE dinámica
	$condiciones = [];
	if ($filtro_usuario) $condiciones[] = "usuario LIKE '%$filtro_usuario%'";
	if ($filtro_tabla)   $condiciones[] = "tabla_afectada LIKE '%$filtro_tabla%'";
	if ($filtro_fecha)   $condiciones[] = "DATE(fecha) = '$filtro_fecha'";
	if ($filtro_accion)  $condiciones[] = "accion LIKE '%$filtro_accion%'";

	$where = count($condiciones) ? 'WHERE ' . implode(' AND ', $condiciones) : '';

	// 06. 	Paginación
	$limite  = 10;
	$pagina  = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
	$inicio  = ($pagina - 1) * $limite;

	// 07. Consultas
	$sql = 
		"SELECT * 
		FROM historial_bd $where 
		ORDER BY fecha 
		DESC 
		LIMIT $inicio, $limite";

	$resultado = $conn->query($sql);
		if (!$resultado) {
	    	die("❌ Error al obtener historial: " . $conn->error);
			}

	// 08. 	Parámetros para paginación
	$sql_total = "SELECT COUNT(*) as total FROM historial_bd $where";
	$total = $conn->query($sql_total)->fetch_assoc();
	$total_paginas = ceil($total['total'] / $limite);

	// Param string para enlaces
	$params = "";
	foreach (['usuario', 'tabla', 'fecha', 'accion'] as $campo) {
	    if (!empty($_GET[$campo])) {
    	    $params .= "&$campo=" . urlencode($_GET[$campo]);
    		}
		}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
    	<meta charset="UTF-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Historial de Cambios – M&I Global Transportes</title>

		<!-- Bootstrap 5 -->
  		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    	    rel="stylesheet"/>

  		<!-- Font Awesome -->
  		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        	rel="stylesheet"/>

  		<!-- DataTables Bootstrap5 CSS -->
  		<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css"
        	rel="stylesheet"/>

	 	<!-- Estilos personalizados -->
		<link rel="stylesheet" href="../../css/base.css">
	</head>
	<body class="bg-light">
		<header class="mb-4 mt-2 ms-1 me-1">
  			<div class="d-flex justify-content-between align-items-center">
    			<h2 class="text-primary fw-bold mb-0">
      				<i class="fas fa-scroll me-2"></i> 
      				Historial de Cambios en la Base de Datos
    			</h2>
    			<a href="../../paneles/panel_admin.php" class="btn btn-secondary">
      				<i class="fa fa-arrow-left"></i> 
					Volver al Panel
    			</a>
  			</div>
		</header>


<main>
        <!-- <h3>🔎 Filtros</h3> -->
	<div class="card mb-4 ms-1 me-1">
		<div class="card-header bg-white">
        	<h5 class="mb-0">
				<i class="fa fa-filter"></i> 
				Filtros de Búsqueda
			</h5>
      	</div>

		<div class="card-body">
        	<form method="GET" action="historial_bd.php" class="row g-3">
				<div class="col-md-3">
            		<label 
						for="usuario" 
						class="form-label">
						Usuario:
					</label>
    	    	    <input 
						type="text"
						id="usuario" 
						name="usuario"
						class="form-control" 
						placeholder="Nombre de usuario"
						value="<?= htmlspecialchars($filtro_usuario) ?>"
					>
				</div>
    	    	<div class="col-md-3">
    	    	    <label 
						for="tabla" 
						class="form-label">
						Tabla:
					</label>
    	    	    <input 
						type="text"
						id="tabla"
						name="tabla"
						class="form-control" 
						placeholder="Nombre de tabla"
						value="<?= htmlspecialchars($filtro_tabla) ?>"
					>
				</div>
    	    	<div class="col-md-3">
        		    <label 
						for="fecha" 
						class="form-label">
						Fecha:
					</label>
        		    <input 
						type="date"
						id="fecha"
						name="fecha"
						class="form-control"
						value="<?= htmlspecialchars($filtro_fecha) ?>"
					>
				</div>
    	    	<div class="col-md-3">
    	    	    <label 
						for="accion" 
						class="form-label">
						Acción:
					</label>
    	   	     	<select 
						id="accion" 
						name="accion" 
						class="form-select"
					>
    	            	<option value="">Todas</option>
    	            	<option value="Creó tabla" <?= $filtro_accion === "Creó tabla" ? "selected" : "" ?>>Creaciones</option>
    	            	<option value="Eliminó tabla" <?= $filtro_accion === "Eliminó tabla" ? "selected" : "" ?>>Eliminaciones</option>
    	            	<option value="Agregó columna" <?= $filtro_accion === "Agregó columna" ? "selected" : "" ?>>Adiciones</option>
    	            	<option value="Modificó columna" <?= $filtro_accion === "Modificó columna" ? "selected" : "" ?>>Modificaciones</option>
    	        	</select>
				</div>
        		<div class="col-12 text-end">

            		<button 
						type="submit" 
						class="btn btn-primary me-2"
					>
						<i class="fa fa-search"></i> 
						Filtrar
					</button>
            		<a 
						href="historial_bd.php" 
						class="btn btn-outline-secondary"
					>
						<i class="fa fa-sync-alt"></i>
						Limpiar
					</a>
        		</div>
			</form>
		</div>
	</div>
   
	<div class="card me-1 ms-1" >
      	<h5 class="text-primary fw-bold mb-4">
  			<i class="fas fa-folder-open me-2"></i> 
			Registros
		</h5>
	
	<div class="card-body p-0">
        <?php if ($resultado->num_rows > 0): ?>
		<div class="table-responsive">
        <table id="tablaHistorial"
                   class="table table-striped table-hover mb-0">

            <thead class="table-dark">
                <tr>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Tabla</th>
                    <th>Fecha</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['usuario']) ?></td>
                    <td><?= htmlspecialchars($fila['accion']) ?></td>
                    <td><?= htmlspecialchars($fila['tabla_afectada']) ?></td>
                    <td><?= htmlspecialchars($fila['fecha']) ?></td>
                    <td><?= htmlspecialchars($fila['ip_usuario']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>

        <!-- <p>⚠️ No se encontraron registros con esos filtros.</p> -->
		<div class="p-4 text-center text-muted">
            ⚠️ No se encontraron registros con ese criterio.
        </div>

		</div>

        <?php endif; ?>
	

	</div>
</div>
    <!-- </section> -->

    <section>
        <h3>📄 Paginación</h3>

        <p>Mostrando página <?= $pagina ?> de <?= $total_paginas ?> (<?= $total['total'] ?> registros)</p>
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="historial_bd.php?pagina=<?= $i . $params ?>" 
               <?= $i === $pagina ? 'style="font-weight:bold"' : '' ?>>
               [<?= $i ?>]
            </a>
        <?php endfor; ?>

	
    </section>

	

</main>

<footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
</footer>
</body>
</html>
