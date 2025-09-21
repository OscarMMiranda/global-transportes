<?php
  	// Activar errores y cabecera UTF-8
  	ini_set('display_errors', 1);
  	ini_set('display_startup_errors', 1);
  	error_reporting(E_ALL);
  	header('Content-Type: text/html; charset=UTF-8');

  	// Meta personalizada para esta vista
  	$pageTitle       = 'Quiénes Somos - Global Transportes';
  	$pageDescription = 'Conocé la historia, misión y valores de Global Transportes S.A.C.';
  	require_once __DIR__ . '/partials/head.php';
?>

<body>
  	<?php require_once __DIR__ . '/partials/header.php'; ?>

	<div class="container-fluid">
  	<div class="row">

		<div class="col-md-3">
    		<?php require_once __DIR__ . '/partials/sidebar.php'; ?>
		</div>

		<div class="col-md-9">
			<!-- <main id="mainContent" role="main" class="app-content px-2"> -->
				<!-- Encabezado de sección -->
				<section class="py-4 mb-2">
        			<!-- <div class="container"> -->
						<h1 class="titulo-principal" mb-4>Quiénes Somos</h1>
        		  		<!-- <h1 class="display-5 mb-3">Quiénes Somos</h1> -->
        		  		<p class="lead">
        		    		Somos una empresa con más de 20 años en el rubro del transporte pesado y la logística nacional.
        		    		Nuestro compromiso es brindar soluciones eficientes, seguras y puntuales a nuestros clientes en todo el país.
        		  		</p>
        			<!-- </div> -->
      			</section>

      			<!-- Misión, Visión, Valores -->
				<section class="py-4 bg-light">
        			<div class="container">
        		  		<div class="row g-4 text-center">

        		    		<!-- Misión -->
      <div class="col-md-4">
        <div class="card card-info h-100 shadow-lg border-0">
          <div class="card-body">
            <i class="fas fa-bullseye fa-3x mb-3 text-primary"></i>
            <h5 class="card-title fw-bold">Misión</h5>
            <p class="card-text">
              Brindar un servicio logístico de excelencia, adaptado a las necesidades de cada cliente.
            </p>
          </div>
        </div>
      </div>
        	    			<!-- Visión -->
      <div class="col-md-4">
        <div class="card card-info h-100 shadow-lg border-0">
          <div class="card-body">
            <i class="fas fa-star fa-3x mb-3 text-warning"></i>
            <h5 class="card-title fw-bold">Visión</h5>
            <p class="card-text">
              Ser referente nacional en transporte de carga pesada y logística integral.
            </p>
          </div>
        </div>
      </div>
        	    			<!-- Valores -->
      <div class="col-md-4">
        <div class="card card-info h-100 shadow-lg border-0">
          <div class="card-body">
            <i class="fas fa-lightbulb fa-3x mb-3 text-success"></i>
            <h5 class="card-title fw-bold">Valores</h5>
            <ul class="list-unstyled">
              <li>✅ Compromiso     </li>
              <li>✅ Responsabilidad</li>
              <li>✅ Innovación    </li>
              <li>✅ Puntualidad</li>
            </ul>
          </div>
        </div>
      </div>
        				</div>
					</div>
      			</section>
    		<!-- </main> -->
		</div>
  	</div>
</div>
  	<?php require_once __DIR__ . '/partials/footer.php'; ?>
