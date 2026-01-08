<?php

	// archivo: /modulos/conductores/index.php
	// Requiere sesión iniciada y usuario autenticado
	// Incluye configuración y utilidades

	// Sesión y seguridad
	if (session_status() === PHP_SESSION_NONE) {
    	session_start();
		}
	if (!isset($_SESSION['usuario'])) {
    	header("Location: /login.php");
    	exit;
		}

	// Variables globales para el módulo
	$titulo    = 'Módulo Conductores';
	$subtitulo = 'Gestión de Conductores';
	$icono     = 'fa-solid fa-id-card-clip';

	// Incluye cabecera HTML (abre <html><head> y configura <title>)
	include __DIR__ . '/componentes/head.php';
?>

<body class="bg-light">
        <?php include __DIR__ . '/../../includes/componentes/header_global.php'; ?>

  <!-- HEADER -->
  <div class="container py-1">
    <?php include __DIR__ . '/componentes/header.php'; ?>

    <?php include __DIR__ . '/componentes/tabs.php'; ?>
  </div>

  <!-- Modales -->
  	<?php include __DIR__ . '/modales/modal_ver_conductor.php'; ?>
  	<?php include __DIR__ . '/modales/modal_conductor.php'; ?>

  <!-- Scripts base -->
  	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Scripts del módulo (modulares) -->
  <script src="/modulos/conductores/assets/datatables.js"></script>
  <script src="/modulos/conductores/assets/modal.js"></script>
  <script src="/modulos/conductores/assets/form.js"></script>
  <script src="/modulos/conductores/assets/acciones.js"></script>

  <script>
    // Auditoría visual: confirmar que index.php cargó correctamente
    console.log('✅ index.php cargado y scripts inicializados');
  </script>

</body>
</html>