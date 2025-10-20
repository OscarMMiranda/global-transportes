<?php
	// archivo	:	/modulos/mantenimiento/tipo_mercaderia/index.php

	session_start();

	// 1) Modo depuración (solo DEV)
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/error_log.txt');

	// 2) Cargar configuración y conexión
	require_once __DIR__ . '/../../../includes/config.php';
	$conn = getConnection();

	// 3) Validaciones defensivas
	if (!isset($conn) || !($conn instanceof mysqli)) {
    	die("❌ Error de conexión con la base de datos.");
	}

	// 4) Mensajes flash
	$msg   = isset($_SESSION['msg'])   ? $_SESSION['msg']   : null;
	$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
	unset($_SESSION['msg'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es">
	<head>
  		<meta charset="UTF-8">
  		<title>Tipos de Mercadería</title>
  		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<!-- Estilos base -->
  		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  		<link rel="stylesheet" href="../../../css/base.css">
  		<link rel="stylesheet" href="../../../css/componentes/header.css">
  		<link rel="stylesheet" href="../../../css/componentes/footer_mercaderia.css">
  		<link rel="stylesheet" href="../../../css/modulos/tipo_mercaderia.css">

  		<!-- ✅ DataTables CSS -->
  		<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

	</head>

	<body class="bg-light d-flex flex-column min-vh-100">

  		<!-- HEADER -->
  		<?php include __DIR__ . '/../componentes/layout/header.php'; ?>

  		<!-- MAIN -->


  		<main class="container py-5 flex-fill">
    		<!-- Encabezado -->
			<?php include 'componentes/encabezado.php'; ?>

    		<!-- Mensajes flash -->
    		<?php include 'componentes/mensajes_flash.php'; ?>

    		<!-- Botón para agregar -->
			<?php include 'componentes/boton_agregar.php'; ?>

    		<!-- Tabs -->
			<?php include 'componentes/tabs.php'; ?>

    		<!-- Contenido dinámico -->
			<?php include 'componentes/contenedores.php'; ?>

  		</main>

  		<!-- FOOTER -->
  		<?php include __DIR__ . '/../componentes/layout/footer_mercaderia.php'; ?>

  		<!-- Modales -->
  		<?php include 'modales/modal_agregar.php'; ?>
  		<?php include 'modales/modal_editar.php'; ?>

  		<!-- Scripts -->
  		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  		
		<!-- ✅ jQuery + DataTables -->
  		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  		<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  		<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

		<!-- ✅ JS del módulo -->
		
		<!-- Scripts del módulo -->
		<script src="js/cargar_activos.js"></script>
		<script src="js/cargar_inactivos.js"></script>
		<script src="js/tipo_mercaderia.js"></script>
		
	</body>
</html>