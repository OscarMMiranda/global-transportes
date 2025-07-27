<?php
  	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
  	error_reporting(E_ALL);
  	header('Content-Type: text/html; charset=UTF-8');

  	$pageTitle       = 'Contacto - Global Transportes';
  	$pageDescription = 'Contáctanos para cotizaciones, consultas logísticas o asistencia personalizada.';
  	require_once __DIR__ . '/partials/head.php';
?>

<body>
  	<?php require_once __DIR__ . '/partials/header.php'; ?>

  	<div class="app-wrapper">
    	<?php require_once __DIR__ . '/partials/sidebar.php'; ?>

    	<main id="mainContent" role="main" class="app-content">
      		<section class="py-5 mb-4">
        		<div class="container">
         			 <h1 class="titulo-principal">Contáctanos</h1>
          			<!-- <h1 class="display-5 mb-3">Contáctanos</h1> -->
          			<p class="lead">Estamos listos para ayudarte. Completá el formulario o contactanos directamente por correo o teléfono.</p>
        		</div>
      		</section>

      		<!-- Formulario de contacto -->
      		<section class="py-4 bg-light">
        		<div class="container">
          			<form action="enviar_contacto.php" method="post" class="row g-4 needs-validation" novalidate>
	            		<div class="col-md-6">
	              			<label for="nombre" class="form-label">Nombre completo</label>
	              			<input type="text" id="nombre" name="nombre" class="form-control" required />
	            		</div>

    	        		<div class="col-md-6">
    	          			<label for="email" class="form-label">Correo electrónico</label>
    	          			<input type="email" id="email" name="email" class="form-control" required />
    	        		</div>

        	    		<div class="col-12">
    	          			<label for="mensaje" class="form-label">Mensaje</label>
    	          			<textarea id="mensaje" name="mensaje" rows="5" class="form-control" required></textarea>
	            		</div>

            			<div class="col-12 text-end">
              				<button type="submit" class="btn btn-primary btn-lg px-4">Enviar consulta</button>
            			</div>
          			</form>
        		</div>
      		</section>
    	</main>
  	</div>

	<?php require_once __DIR__ . '/partials/footer.php'; ?>
