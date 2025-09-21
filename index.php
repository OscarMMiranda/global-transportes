<?php
	//	archivo	:	index.php – Punto de entrada del sitio.

     // Carga los partials estructurales con control de errores, trazabilidad y debug opcional.
 

	// 01. Configuración del entorno
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	header('Content-Type: text/html; charset=UTF-8');

	// 02. Activador de modo debug
	define('DEBUG_MODE', false); // cambiar a true si se necesita rastreo de pasos
	define('SITE_LOADED', true); // protección contra ejecución directa en partials

function logStep($mensaje) {
    if (DEBUG_MODE) echo "<small style='color:gray'>[DEBUG] $mensaje</small><br>";
}

// 03. Auditoría básica de acceso
$log = date('Y-m-d H:i:s') . ' | IP: ' . $_SERVER['REMOTE_ADDR'] . ' | UA: ' . $_SERVER['HTTP_USER_AGENT'] . "\n";
file_put_contents(__DIR__ . '/logs/accesos.log', $log, FILE_APPEND);

// 04. Carga de estructura modular con validación
logStep('Inicio de index.php');

// HEAD y HEADER deben ir antes del <body>
require_once __DIR__ . '/partials/head.php';
require_once __DIR__ . '/partials/header.php';

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
            ?>
        </div>

        <!-- Contenido principal -->
        <div class="col-md-9">
            <?php
            if (file_exists(__DIR__ . '/partials/hero.php')) {
                require_once __DIR__ . '/partials/hero.php';
                logStep('hero.php cargado');
            }

            if (file_exists(__DIR__ . '/partials/ventajas.php')) {
                require_once __DIR__ . '/partials/ventajas.php';
                logStep('ventajas.php cargado');
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

logStep('Final de ejecución');
?>