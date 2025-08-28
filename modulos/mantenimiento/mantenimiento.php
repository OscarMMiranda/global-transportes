<?php
    session_start();

	// 1) Modo depuración (solo DEV)
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors',     1);
	ini_set('error_log',      __DIR__ . '/error_log.txt');

	// 2) Cargar configuración y conexión
	require_once __DIR__ . '/../../includes/config.php';
	$conn = getConnection();	
    
	
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

			<!-- Estilos extra -->
  			<style>
    		/* Limita el ancho de cada col y centra el grid */
    			.dashboard-cards {
      				justify-content: center;
    				}
    			.dashboard-cards .col {
      				max-width: 280px;
    				}

    		/* Animación sutil al pasar el ratón */
    			.card-dashboard {
      				transition: transform .3s, box-shadow .3s;
    				}
    			.card-dashboard:hover {
      				transform: translateY(-5px);
      				box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
    				}

    		/* Botones un poco más compactos */
    			.dashboard-btn {
      				font-size: .9rem;
      				padding: .5rem 1rem;
    				}
  			</style>

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
          					<article class="card card-dashboard h-80 shadow-sm">
            					<div class="card-body d-flex flex-column">
              						<h3 class="card-title h6">📦 Tipo de Mercadería</h3>
              						<p class="card-text flex-fill">
                						Editar y actualizar tipos de mercadería.
              						</p>
              						<a 
                						href="tipo_mercaderia/index.php"
                						class="btn dashboard-btn btn-primary mt-2"
              						>
                						Actualizar
              						</a>
            					</div>
          					</article>
        				</div>

        				<!-- Tipo de vehiculos -->
        				<div class="col">
          					<article class="card card-dashboard h-80 shadow-sm">
            					<div class="card-body d-flex flex-column">
              						<h3 class="card-title h6">📦 Tipo de Vehiculos</h3>
              						<p class="card-text flex-fill">
                						Gestionar tipo de vehiculos.
              						</p>
              						<a 
                						href="tipo_vehiculo/index.php" 
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

						<!-- Tipo de Documentos -->
						<div class="col">
  							<article class="card card-dashboard h-100 shadow-sm">
    							<div class="card-body d-flex flex-column">
      								<h3 class="card-title h6">📄 Tipo de Documentos</h3>
      								<p class="card-text flex-fill">
        								Administrar tipos de documentos (DNI, RUC, Pasaporte, etc).
      								</p>
      								<a 
        								href="tipo_documento/editar_tipo_documento.php" 
        								class="btn dashboard-btn btn-primary mt-3"
      								>
        								Actualizar
      								</a>
    							</div>
  							</article>
						</div>

						<!-- Módulo Zonas -->
  						<div class="col">
    						<article class="card card-dashboard h-100 shadow-sm">
      							<div class="card-body d-flex flex-column">
        							<h3 class="card-title h6">📍 Zonas</h3>
        							<p class="card-text flex-fill">
          								Gestionar zonas geográficas y permisos por región.
        							</p>
        							<a 
										href="zonas/editar_zonas.php" 
										class="btn dashboard-btn btn-primary mt-3">
          								Administrar Zonas
        							</a>
      							</div>
    						</article>
  						</div>

						<!-- Valores Referenciales -->
						<div class="col">
  							<article class="card card-dashboard h-100 shadow-sm">
    							<div class="card-body d-flex flex-column">
      								<h3 class="card-title h6">💰 Valores Referenciales</h3>
      								<p class="card-text flex-fill">
        								Gestionar valores anuales por zona y tipo de carga.
      								</p>
      								<a 
        								href="valores_referenciales/editar_valores.php" 
        								class="btn dashboard-btn btn-primary mt-3"
      								>
        								Administrar Valores
      								</a>
    							</div>
  							</article>
						</div>

						<!-- Valores Referenciales -->
						<div class="col">
  							<article class="card card-dashboard h-100 shadow-sm">
    							<div class="card-body d-flex flex-column">
      								<h3 class="card-title h6">🧍 Conductores</h3>
      								<p class="card-text flex-fill">
        								Administración del personal de conducción, licencias, documentos y asignaciones.
      								</p>
      								<a href="../conductores/index.php" 
         								class="btn dashboard-btn btn-primary mt-3">
        									Actualizar
      								</a>
    							</div>
  							</article>
						</div>

						<!-- Valores Referenciales -->
						<div class="col">
  							<article class="card card-dashboard h-100 shadow-sm">
    							<div class="card-body d-flex flex-column">
      								<h3 class="card-title h6">🧍 T D M</h3>
      								<p class="card-text flex-fill">
        								Administración TDM.
      								</p>
      								<a href="tipo_mercaderia/index.php" 
         								class="btn dashboard-btn btn-primary mt-3">
        									Actualizar
      								</a>
    							</div>
  							</article>
						</div>

						<!-- Lugares -->
						<div class="col">
  							<article class="card card-dashboard h-100 shadow-sm">
    							<div class="card-body d-flex flex-column">
      								<h3 class="card-title h6">Lugares</h3>
      								<p class="card-text flex-fill">
        								Almacenes, depositos, terminales.
      								</p>
      								<a href="tipo_mercaderia/index.php" 
         								class="btn dashboard-btn btn-primary mt-3">
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
