<?php
    session_start();
    require_once __DIR__ . '/../../includes/conexion.php';

    // Solo admins
    if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') 
        {
        header('Location: ../sistema/login.php');
        exit;
        }
?>

<!DOCTYPE html>
	<html lang="es">
		
		<head>
    		<meta charset="UTF-8">
    		<meta name="viewport" content="width=device-width, initial-scale=1">
    		<title>Mantenimiento de Datos – Global Transportes</title>

    		<!-- Bootstrap 5 -->
    		<link 
        		href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
        		rel="stylesheet"
    		/>
    		<!-- FontAwesome (si usas iconos como <i class="fas fa-arrow-left"></i>) -->
    		<link 
        		rel="stylesheet" 
        		href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    		/>
    		<!-- Estilos locales -->
    		<link rel="stylesheet" href="../../css/base.css">
    		<link rel="stylesheet" href="../../css/dashboard.css">
		</head>



		
		<body class="bg-light d-flex flex-column min-vh-100">

  			<!-- HEADER -->
  			<header class="dashboard-header bg-white shadow-sm py-3">
    			<div class="container d-flex align-items-center justify-content-between">
      				<h1 class="h4 mb-0">🛠 Mantenimiento de Datos</h1>
      				<a 
        				href="../erp_dashboard.php" 
        				class="btn btn-outline-primary btn-sm"
      					>
        				<i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
      				</a>
    			</div>
  			</header>

  			<!-- MAIN -->
  			<main class="dashboard-container flex-fill container py-4">
    			<section class="dashboard-section">
      				<h2 class="h5 mb-4">Seleccione la tabla a actualizar</h2>
      				<div class="row row-cols-1 row-cols-md-2 g-4 dashboard-cards">
    
						<!-- Tipo de Mercadería -->
        				<div class="col">
          					<article class="card card-dashboard h-100 shadow-sm">
            					<div class="card-body d-flex flex-column">
              						<h3 class="card-title h6">📦 Tipo de Mercadería</h3>
              						<p class="card-text flex-fill">
                						Editar y actualizar tipos de mercadería.
              						</p>
              						<a 
                						href="tipo_mercaderia/editar_tipo_mercaderia.php" 
                						class="btn dashboard-btn btn-primary mt-3"
              						>
                						Actualizar
              						</a>
            					</div>
          					</article>
        				</div>

        				<!-- Tipo de vehiculos -->
        				<div class="col">
          					<article class="card card-dashboard h-100 shadow-sm">
            					<div class="card-body d-flex flex-column">
              						<h3 class="card-title h6">📦 Tipo de Vehiculos</h3>
              						<p class="card-text flex-fill">
                						Gestionar tipo de vehiculos.
              						</p>
              						<a 
                						href="tipo_vehiculo/editar_tipo_vehiculo.php" 
                						class="btn dashboard-btn btn-primary mt-3"
              						>
                						Actualizar
              						</a>
            					</div>
          					</article>
        				</div>

						<!-- Categorías de Mercadería -->
        				<div class="col">
          					<article class="card card-dashboard h-100 shadow-sm">
            					<div class="card-body d-flex flex-column">
              						<h3 class="card-title h6">📦 Agencia de Aduanas</h3>
              						<p class="card-text flex-fill">
                						Gestionar agencias de aduanas.
              						</p>
              						<a 
                						href="agencia_aduana/editar_agencia_aduana.php" 
                						class="btn dashboard-btn btn-primary mt-3"
              						>
                						Actualizar
              						</a>
            					</div>
          					</article>
        				</div>
      				</div>
    			</section>
  			</main>

  			<!-- FOOTER -->
  			<footer class="footer bg-white text-center py-3 mt-auto">
    			<div class="container">
      				<small class="text-muted">
        				&copy; 2025 Global Transportes. Todos los derechos reservados.
      				</small>
    			</div>
  			</footer>

  			<!-- JS de Bootstrap -->
  			<script 
    			src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  			></script>
		</body>
	</html>
