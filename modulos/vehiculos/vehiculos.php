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

	require_once '../../includes/header_erp.php'; 
	require_once '../../includes/funciones.php'; 

	// 1) Traer activos e inactivos por separado
	$vehiculos_activos   = obtenerVehiculos($conn, 1);
	$vehiculos_inactivos = obtenerVehiculos($conn, 0);
	
	// 2) 	Historial
	registrarEnHistorial(
    	$_SESSION['usuario'],
    	'Visualizó listado de vehículos',
    	'vehiculos',
    	$_SERVER['REMOTE_ADDR']
    	);

?>

<div class="container mt-4">
	<h2 class="text-center mb-4">Listado de Vehículos</h2>
	
	<div class="d-flex justify-content-end mb-3">
    	<a href="form_vehiculos.php" 
			class="btn btn-primary">
			+ Agregar Nuevo Vehículo
		</a>
	</div>

	<!-- Nav tabs -->
	<ul class="nav nav-tabs mb-3" id="tabVehiculos" role="tablist">
    	<li class="nav-item" role="presentation">
      		<button 
				class="nav-link active"
				id="tab-activos" 
				data-bs-toggle="tab" 
				data-bs-target="#activos"
				type="button"
				role="tab"
				aria-controls="activos"
        		aria-selected="true"
				>
        		🟢 Activos
      		</button>
    	</li>
    	<li class="nav-item" role="presentation">
     		<button 
				class="nav-link"
				id="tab-inactivos"
				data-bs-toggle="tab" 
				data-bs-target="#inactivos"
				type="button"
              	role="tab"
				aria-controls="inactivos"
        		aria-selected="false"
				>
        		🔘 Inactivos
      		</button>
    	</li>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
    	<!-- Activos -->
    	<div 
			class="tab-pane fade show active" 
			id="activos" 
			role="tabpanel"
			aria-labelledby="tab-activos"
		>
      		<?= generarTablaVehiculos($vehiculos_activos, true) ?>
    	</div>

    	<!-- Inactivos -->
    	<div 
			class="tab-pane fade" 
			id="inactivos" 
			role="tabpanel"
			aria-labelledby="tab-inactivos"
		>
      		<?= generarTablaVehiculos($vehiculos_inactivos, false) ?>
    	</div>
  	</div>
</div>

<?php
	/**
 	* Genera una tabla HTML con acciones según el flag $activo
 	* @param mysqli_result $resultSet
 	* @param bool $activo
 	* @return string
 	*/

	function generarTablaVehiculos($rs, $activo = true) {
  	ob_start();
?>
<div class="table-responsive">
	<table class="table table-striped table-hover">
    	<thead class="table-dark">
    	  	<tr>
        		<th>ID</th>
				<th>Placa</th>
				<th>Marca</th>
				<th>Modelo</th>
        		<th>Tipo</th>
				<th>Año</th>
				<th>Empresa</th>
				<th>Acciones</th>
      		</tr>
    	</thead>
    	<tbody>
      		<?php while ($row = $rs->fetch_assoc()): ?>
      		<tr class="<?= $activo ? '' : 'table-secondary text-muted' ?>">
        		<td><?= $row['id'] ?></td>
        		<td><?= htmlspecialchars($row['placa']) ?></td>
        		<td><?= htmlspecialchars($row['marca']) ?></td>
        		<td><?= htmlspecialchars($row['modelo']) ?></td>
        		<td><?= htmlspecialchars($row['tipo']) ?></td>
        		<td><?= htmlspecialchars($row['anio']) ?></td>
        		<td><?= htmlspecialchars($row['empresa']) ?></td>
        		<td>
          			<a href="ver_vehiculo.php?id=<?= $row['id'] ?>"
             			class="btn btn-info btn-sm">
					 	👁️ Ver
					</a>

          			<?php if ($activo): ?>
            			<a href="editar_vehiculo.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
            			<form 
							action="eliminar_vehiculo.php" 
							method="post" 
							class="d-inline">
            	  			<input 
								type="hidden" 
								name="id" 
								value="<?= $row['id'] ?>">
              				<button 
								class="btn btn-danger btn-sm"
                	    	  	onclick="return confirm('¿Eliminar este vehículo?')">
                				🗑️
              				</button>
            			</form>

          			<?php else: ?>
            			<form 
							action="restaurar_vehiculo.php" 
							method="post" 
							class="d-inline">
              				<input 
								type="hidden" 
								name="id" 
								value="<?= $row['id'] ?>">
              				<button 
								class="btn btn-success btn-sm"
                	      		onclick="return confirm('¿Restaurar este vehículo?')">
                				✅
              				</button>
            			</form>
          			<?php endif; ?>
        		</td>
      		</tr>
      		<?php endwhile; ?>
    	</tbody>
	</table>
</div>
<?php
	return ob_get_clean();
	}
?>

<!-- Bootstrap JS (necesario para tabs, modales, etc.) -->
<script
	src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
</script>

<!-- Script para persistir pestaña y cargar modal AJAX -->
<script>
	document.addEventListener("DOMContentLoaded", () => {
  	// 1) Persistir última pestaña activa
  	const tabs = document.querySelectorAll('button[data-bs-toggle="tab"]');
  	// tabs.forEach(tab =>
    // tab.addEventListener("shown.bs.tab", (e) => {
    // 	localStorage.setItem("vehiculoTabActivo", e.target.dataset.bsTarget);

	document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
    tab.addEventListener("shown.bs.tab", e => {
      localStorage.setItem("vehiculoTabActivo", e.target.getAttribute("data-bs-target"));
   
    	})
  	);
  	const lastTab = localStorage.getItem("vehiculoTabActivo");
  	if (lastTab) {
    	const btn = document.querySelector(`button[data-bs-target="${lastTab}"]`);
    	if (btn) new bootstrap.Tab(btn).show();
  		}

  	// 2) AJAX para botón Ver
  	// const modal = new bootstrap.Modal("#modalVerVehiculo");
  	const modal = new bootstrap.Modal(document.getElementById("modalVerVehiculo"));
 
  	document.querySelectorAll(".btn-ver").forEach(btn => {
    	btn.addEventListener("click", async () => {
      		// const id = btn.dataset.id;
			const id = btn.getAttribute("data-id");
      		const resp = await fetch(`ver_vehiculo.php?id=${id}&ajax=1`);
      		const html = await resp.text();
      		// document.querySelector("#modalVerVehiculo .modal-body").innerHTML = html;
      		document.querySelector("#modalVerVehiculo .modal-body").innerHTML = await resp.text();
     
			modal.show();
    		});
  		});
  	});
</script>

	

<?php
	require_once '../../includes/footer_erp.php'; 
	$conn->close();

?>

