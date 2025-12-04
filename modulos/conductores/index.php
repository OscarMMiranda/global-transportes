<?php
	//
	//	

	// archivo: /modulos/conductores/index.php

	if (session_status() === PHP_SESSION_NONE) {
    	session_start();
		}
	if (!isset($_SESSION['usuario'])) {
    	header("Location: /login.php");
    	exit;
		}
	$titulo = 'Módulo Conductores';
?>

<?php include __DIR__ . '/componentes/head.php'; ?>

	<body class="bg-light">

  		<!-- HEADER -->
  		<div class="container py-1">
    		<?php
    	  		$titulo = 'Módulo Conductores';
    	  		$icono = 'fa-solid fa-id-card-clip';
    	  		include __DIR__ . '/componentes/header.php';
    		?>

    		<!-- Pestañas del módulo (ya incluye las tablas) -->
    		<?php include __DIR__ . '/componentes/tabs.php'; ?>
  		</div>

  		<!-- Modales -->
  		<?php include __DIR__ . '/modales/modal_ver_conductor.php'; ?>
  		<?php include __DIR__ . '/modales/modal_conductor.php'; ?>

  		<!-- Scripts -->
  		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  		<!-- DataTables -->
  		<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  		<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  		<!-- Script del módulo -->
  		<script src="/modulos/conductores/assets/conductores.js"></script>
	</body>
</html>

