<?php
	// modulos/asignaciones/index.php

	// 1. Configuración, conexión y helpers
	require_once __DIR__ . '/../../includes/config.php';
	require_once __DIR__ . '/../../includes/conexion.php';
	require_once __DIR__ . '/../../includes/helpers.php';

	// 2. Sesión y trazabilidad
	if (session_status() === PHP_SESSION_NONE) {
    	session_start();
		}
	if (isset($_SESSION['usuario'])) {
    	registrarActividad(
        	$conn,
        	$_SESSION['usuario'],
        	'Accedió al módulo de Asignaciones'
    		);
		}

	// 3. Partials globales: head y header
	// require_once __DIR__ . '/../../partials/head.php';
	require_once __DIR__ . '/../../includes/header-1.php';

?>


<!-- DataTables CSS (añádelo justo después de tu CSS de Bootstrap) -->
<link
  rel="stylesheet"
  href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"
/>



<!-- Contenido principal -->
<main class="col py-4">
	<h3 
		class="mb-4">
		Asignaciones – Conductores / Tractos / Carretas
	</h3>

	<button 
		class="btn btn-success mb-3 shadow-sm" 
		data-bs-toggle="modal" 
		data-bs-target="#modalAsignar"
	>
  		<i class="fas fa-plus me-1"></i> 
		Nueva asignación
	</button>

	<!-- Tabla de asignaciones -->
	<table 
		id="tablaAsignaciones"
    	class="table table-bordered table-striped table-hover align-middle text-center">
  		<thead class="table-dark">
    		<tr>
      			<th>Conductor</th>
				<th>Tracto</th>
				<th>Carreta</th>
      			<th>Inicio</th>
				<th>Fin</th>
				<th>Estado</th>
				<th>Acción</th>
    		</tr>
  		</thead>
  		<tbody></tbody>
	</table>
</main>

<!-- Modal Partial -->
  <?php 
    require_once __DIR__ . '/views/modal_asignacion.php'; 
  ?>

<?php
	// 4. Footer global y scripts del módulo
	require_once __DIR__ . '/../../includes/footer-asigna.php';
?>

<!-- Carga de scripts -->
<!-- <script>
  window.ASIGNACIONES_API_URL = '/modulos/asignaciones/api.php';
</script> -->

<!-- 7. Define la URL base de tu API justo después de cargar jQuery/Bootstrap -->
<script>
  // Si tu index.php está en /modulos/asignaciones/, api.php está en la misma carpeta
  window.ASIGNACIONES_API_URL = 'api.php';
</script>



<!-- <script src="modulos/asignaciones/js/asignaciones.js" defer></script> -->
<!-- <script src="js/asignaciones.js" defer></script> -->

<!-- 8. Incluye tu script principal de asignaciones -->
<script src="modulos/asignaciones/js/asignaciones.js" defer></script>