<?php
	// correo.php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require_once __DIR__ . '/includes/config.php';
	require_once __DIR__ . '/includes/conexion.php';
	require_once __DIR__ . '/includes/helpers.php';
	require_once __DIR__ . '/partials/head.php';

	if (session_status() === PHP_SESSION_NONE) {
	    session_start();
	}

	if (isset($_SESSION['usuario'])) {
	    registrarActividad($conn, $_SESSION['usuario'], 'Accedió al módulo de correo');
	}

	// 2) HEADER: tu barra de navegación superior
	require_once __DIR__ . '/includes/header-1.php';
?>


<div class="container-fluid">
  <div class="row flex-nowrap">
  		<aside 
			class="col-auto bg-light p-3 border-end" 
           	style=" width:250px; height: 80px;"
		>
      		<?php require_once __DIR__ . '/partials/sidebar.php'; ?>
    	</aside>

    	<!-- Contenido principal -->
    	<!-- <div class="flex-grow-1 p-4"> -->
		<main class="col py-4">
    		<div class="card shadow-sm">
      			<div class="card-header bg-primary text-white">
            	    <!-- <h5 class="mb-0 d-flex align-items-center"> -->
					 <h5 class="mb-0">
    					<i class="fas fa-envelope-open-text me-2 text-white"></i>
    					Acceso al correo corporativo
					</h5>
            	</div>
        		<div class="card-body">
          			<p>Este módulo te permite acceder al sistema de correo corporativo. Por seguridad, se abrirá en una nueva pestaña.</p>
          			<p>Si tienes problemas para acceder, asegúrate de tener tus credenciales actualizadas.</p>
            	
            		<a 
            	    	href="http://www.globaltransportes.com:2084" 
            	    	target="_blank" 
            	    	rel="noopener noreferrer" 
            	    	class="btn btn-outline-primary"
            	    	aria-label="Abrir correo corporativo en una nueva pestaña"
            		>
            			Abrir correo en nueva pestaña
            		</a>

        		</div>
      		</div>
		</main>
    	<!-- </div> -->
  	</div> <!-- row -->
</div> <!-- container-fluid -->

<?php require_once __DIR__ . '/partials/footer.php'; ?>
