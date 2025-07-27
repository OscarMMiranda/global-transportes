<?php
  	// Activar errores y cabecera UTF-8
  	ini_set('display_errors', 1);
  	ini_set('display_startup_errors', 1);
  	error_reporting(E_ALL);
  	header('Content-Type: text/html; charset=UTF-8');

  	// Metadatos para esta vista
  	$pageTitle       = 'Servicios - Global Transportes';
  	$pageDescription = 'Soluciones logísticas, transporte de carga pesada y seguimiento en tiempo real.';
  	require_once __DIR__ . '/partials/head.php';
?>

<body>
	<?php require_once __DIR__ . '/partials/header.php'; ?>

	<div class="app-wrapper">
    	<?php require_once __DIR__ . '/partials/sidebar.php'; ?>

    	<main id="mainContent" role="main" class="app-content">
      		<section class="py-5 mb-4">
        		<div class="container">
          			<h1 class="titulo-principal">Nuestros Servicios</h1>
          			<!-- <h1 class="display-5 mb-3">Nuestros Servicios</h1> -->
          			<p class="lead">
            			Brindamos soluciones adaptadas a cada cliente, 
						combinando eficiencia, seguridad y tecnología.
          			</p>
        		</div>
      		</section>

      		<!-- Tarjetas de servicios -->
      		<section class="py-4 bg-light">
        		<div class="container">
          			<div class="row g-4">
            			<div class="col-md-6 col-lg-3">
              				<div class="card h-100 shadow-sm p-3">
                				<h5 class="card-title">
									🚚 Transporte de Carga
								</h5>
                				<p class="card-text">
                  					Traslados pesados a nivel nacional con monitoreo constante.
                				</p>
              				</div>
            			</div>
            			<div class="col-md-6 col-lg-3">
              				<div class="card h-100 shadow-sm p-3">
                				<h5 class="card-title">
									⏱ Seguimiento en Tiempo Real
								</h5>
                				<p class="card-text">
                  					Visualizá la ubicación exacta de tu envío desde cualquier dispositivo.
                				</p>
              				</div>
            			</div>
            			<div class="col-md-6 col-lg-3">
              				<div class="card h-100 shadow-sm p-3">
                				<h5 class="card-title">
									📦 Logística Personalizada
								</h5>
                				<p class="card-text">
                  					Diseñamos el flujo ideal para tu operación, desde el origen hasta el destino.
                				</p>
              				</div>
            			</div>
            			<div class="col-md-6 col-lg-3">
              				<div class="card h-100 shadow-sm p-3">
                				<h5 class="card-title">
									🔐 Custodia Especializada
								</h5>
                				<p class="card-text">
                  					Servicio premium para cargas sensibles, con protocolos de seguridad reforzada.
                				</p>
              				</div>
            			</div>
          			</div>
        		</div>
      		</section>
    	</main>
  	</div>

  <?php require_once __DIR__ . '/partials/footer.php'; ?>
