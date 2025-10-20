<?php
	// archivo : index.php – Punto de entrada del sitio con trazabilidad y reversibilidad

	// 01. Configuración del entorno
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	header('Content-Type: text/html; charset=UTF-8');

	// 02. Activador de modo debug
	define('DEBUG_MODE', false); // cambiar a true si se necesita rastreo de pasos
	define('SITE_LOADED', true); // protección contra ejecución directa en partials

	function logStep($mensaje) {
    	if (DEBUG_MODE) {
        	echo "<small style='color:gray'>[DEBUG] " . $mensaje . "</small><br>";
    		}
		}

	// 03. Auditoría básica de acceso
	$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'anonimo';
	$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	$log = date('Y-m-d H:i:s') . " | Usuario: " . $usuario . " | IP: " . $_SERVER['REMOTE_ADDR'] . " | UA: " . $_SERVER['HTTP_USER_AGENT'] . " | URI: " . $uri . "\n";
	file_put_contents(__DIR__ . '/logs/accesos.log', $log, FILE_APPEND);

	// 04. Carga de estructura modular con validación
	logStep('Inicio de index.php');

	// HEAD y HEADER deben ir antes del <body>
	if (file_exists(__DIR__ . '/partials/head.php')) {
    	require_once __DIR__ . '/partials/head.php';
    	logStep('head.php cargado');
		}
	if (file_exists(__DIR__ . '/partials/header.php')) {
    	require_once __DIR__ . '/partials/header.php';
    	logStep('header.php cargado');
		}
?>

<!-- 05. Layout visual -->
<div class="container-fluid mt-5 pt-4">
	<div class="row">

        <!-- Sidebar lateral -->
        <div class="col-md-3">
            <?php
            if (file_exists(__DIR__ . '/partials/sidebar.php')) {
                require_once __DIR__ . '/partials/sidebar.php';
                logStep('sidebar.php cargado');
            	} 
			elseif (DEBUG_MODE) {
                echo "<div class='alert alert-warning'>Partial faltante: sidebar.php</div>";
            	}
            ?>
        </div>

        <!-- Contenido principal -->
        <div class="col-md-9">
            <?php
            if (file_exists(__DIR__ . '/partials/hero.php')) {
                require_once __DIR__ . '/partials/hero.php';
                logStep('hero.php cargado');
            	} 
			elseif (DEBUG_MODE) {
                echo "<div class='alert alert-warning'>Partial faltante: hero.php</div>";
            	}

            if (file_exists(__DIR__ . '/partials/ventajas.php')) {
                require_once __DIR__ . '/partials/ventajas.php';
                logStep('ventajas.php cargado');
            	} 
			elseif (DEBUG_MODE) {
                echo "<div class='alert alert-warning'>Partial faltante: ventajas.php</div>";
            	}
            ?>
        </div>

    </div> <!-- Fin row -->
</div> <!-- Fin container-fluid -->

<?php
	// Pie de página
	if (file_exists(__DIR__ . '/partials/footer.php')) {
    	require_once __DIR__ . '/partials/footer.php';
    	logStep('footer.php cargado');
		} 
	elseif (DEBUG_MODE) {
    	echo "<div class='alert alert-warning'>Partial faltante: footer.php</div>";
		}

	logStep('Final de ejecución');
?>