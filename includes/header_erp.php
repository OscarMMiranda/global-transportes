<?php
	//session_start(); // Iniciar sesión para gestionar autenticación

	if (session_status() === PHP_SESSION_NONE) {
    	session_start();
		}
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta 
			name="viewport" 
			content="width=device-width, initial-scale=1.0">
		<title>ERP Global Transportes</title>
    
		<!-- Bootstrap CSS -->
		<link
    		href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    		rel="stylesheet"
  		>
		
		<link rel="stylesheet" href="../../css/base.css"> <!-- Tus estilos personalizados -->
	
		<!-- FontAwesome (opcional) -->
  		<link
  			href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  			rel="stylesheet"
  			crossorigin="anonymous"
  			referrerpolicy="no-referrer"	
		/>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	
		<!-- Tus estilos específicos -->
  		<link
    		href="<?= BASE_URL ?>assets/css/clientes.css"
    			rel="stylesheet"
  		>

		<?php if (defined('MODULE_CSS')): ?>
  			<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/<?= MODULE_CSS ?>">
		<?php endif; ?>



	</head>
	
	<body>
	<!-- Barra superior de sesión -->
	<!-- <nav class="navbar navbar-dark bg-primary p-2"> -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    	<div class="container-fluid d-flex justify-content-between">
        	<span class="navbar-brand mb-0 h1">ERP Global Transportes</span>
        	<div>
            	<?php if (isset($_SESSION['usuario'])) { ?>
                	<span class="text-white me-3">Bienvenido, <?= htmlspecialchars($_SESSION['usuario']); ?></span>
                	<a href="../../sistema/logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
            	<?php } else { ?>
                	<a href="../../login.php" class="btn btn-success btn-sm">Iniciar Sesión</a>
            	<?php } ?>
        	</div>
    	</div>
	</nav>

	<!-- Menú de navegación dentro del ERP -->
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
    	<div class="container">
        	<a class="navbar-brand" href="../panel.php">
        	    <img src="../../img/logo.png" alt="Global Transportes" width="150">
        	</a>
        	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuERP">
        	    <span class="navbar-toggler-icon"></span>
        	</button>

        	<div class="collapse navbar-collapse" id="menuERP">
            	<ul class="navbar-nav ms-auto">
                	<li class="nav-item"><a class="nav-link" href="../../modulos/erp_dashboard.php">Dashboard</a></li>
                	<li class="nav-item"><a class="nav-link" href="../vehiculos/vehiculos.php">Vehículos</a></li>
                	<li class="nav-item"><a class="nav-link" href="../clientes/clientes.php">Clientes</a></li>
                	<li class="nav-item"><a class="nav-link" href="../documentos/documentos.php">Documentos</a></li>
                	<li class="nav-item"><a class="nav-link" href="../empleados/empleados.php">Empleados</a></li>
                	<!-- 
                	    <li class="nav-item"><a class="nav-link" href="../sistema/historial_bd.php">Historial BD</a></li>
                	-->
                	<li class="nav-item"><a class="nav-link" href="../../sistema/ayuda.php">Ayuda</a></li>
            	</ul>
        	</div>
    	</div>
	</nav>

	<div class="container mt-4">
	</div>