<?php
  /**
   * ventajas.php
   * Sección modular que muestra las principales ventajas del servicio.
   * Los datos se cargan desde un arreglo para facilitar escalabilidad y mantenimiento.
   */

  	$ventajas = [
    	[
    	'icon' => 'fa-solid fa-truck-fast',
      	'titulo' => 'Flota Moderna',
      	'descripcion' => 'Vehículos equipados con tecnología de punta'
    	],

    	[
      	'icon' => 'fa-solid fa-location-dot',
      	'titulo' => 'Seguimiento en Tiempo Real',
      	'descripcion' => 'Estado y ubicación de tu envío en todo momento'
    	],

    	[
      	'icon' => 'fa-solid fa-shield-halved',
      	'titulo' => 'Transporte Seguro',
      	'descripcion' => 'Garantizamos un traslado eficiente y protegido'
    	],

    	[
      	'icon' => 'fa-solid fa-map',
      	'titulo' => 'Cobertura Nacional',
      	'descripcion' => 'Llegamos a todos los rincones del país con puntualidad'
    	],
  	];
?>

	<div class="container py-0">
		<!-- <h2 class="text-center mb-4">Ventajas del Servicio</h2> -->
  		<h2 class="text-center mb-2">¿Por qué elegirnos?</h2>
  		
		<!-- <div class="col-sm-6 col-lg-3"> -->
		<div class="row">
    		<?php foreach ($ventajas as $ventaja): ?>
      		
			<div class="col-md-6 col-lg-3 mb-4">
          		<div class="card h-100 text-center shadow-sm p-3">
					<div class="card-body text-center">
						<i class="<?= $ventaja['icon'] ?> fa-2x mb-3 text-primary" aria-hidden="true"></i>
            			 <!-- Visible para usuarios -->
						<h5 class="card-title"><?= $ventaja['titulo'] ?></h5>

						<!-- Solo para lectores de pantalla -->
            			<span class="visually-hidden"><?= $ventaja['titulo'] ?></span>
						<p class="card-text"><?= $ventaja['descripcion'] ?></p>
          			</div>
        		</div>
      		</div>
    		<?php endforeach; ?>
  		</div>
	</div>

