<?php
  	/**
   	* index.php
   	* Punto de entrada del sitio.
   	* Carga los partials estructurales con control de errores y debug opcional.
   	* DEBUG_MODE permite rastrear pasos durante desarrollo.
   	*/

  	// Configuración del entorno
  	ini_set('display_errors', 1);
  	ini_set('display_startup_errors', 1);
  	error_reporting(E_ALL);
  	header('Content-Type: text/html; charset=UTF-8');

  	// Activador de modo debug
  	define('DEBUG_MODE', false); // cambiar a true si se necesita rastreo de pasos
	function logStep($mensaje) {
    	if (DEBUG_MODE) echo "[DEBUG] $mensaje<br>";
  		}

  	// Carga secuencial de partials
  	logStep('Inicio de index.php');

	// Carga de partials superiores
  	require_once __DIR__ . '/partials/head.php';
  	logStep('head.php cargado');

	
  	require_once __DIR__ . '/partials/header.php';
  	logStep('header.php cargado');

	echo '<div class="container-fluid">';
  	echo 	'<div class="row">';

	echo 		'<div class="col-md-3">';
  					require_once __DIR__ . '/partials/sidebar.php';
  					logStep('sidebar.php cargado');
  	echo 		'</div>';

  	echo 		'<div class="col-md-9">';
  					require_once __DIR__ . '/partials/hero.php';
  					logStep('hero.php cargado');
	// Sección de ventajas
  					require_once __DIR__ . '/partials/ventajas.php';
	
  	echo 		'</div>';
	

  	echo 	'</div>'; // Fin de .row
	
  	echo '</div>'; // Fin de .container-fluid

  
	// Pie de página
  	require_once __DIR__ . '/partials/footer.php';
  	logStep('footer.php cargado');

  	logStep('Final de ejecución');
?>
