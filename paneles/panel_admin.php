<?php
	// panel_admin.php
	session_start();

	// 1)	Mostrar errores en desarrollo (quitar en producción)
	ini_set('display_errors', 			1);
	ini_set('display_startup_errors', 	1);
	error_reporting(E_ALL);

	// 2) 	Incluir conexión y helpers
	// require_once '../includes/conexion.php';

	// 2) Cargar config y obtener la conexión
	require_once __DIR__ . '/../includes/config.php';


	// config.php ya hace `require_once 'conexion.php'`
	// y define la función getConnection()
	$conn = getConnection();

	// 3) Cargar helpers y funciones
	require_once '../includes/helpers.php';
	require_once '../includes/funciones.php';

	// includes/helpers.php
	if (! function_exists('handleExportCSV')) {
    	function handleExportCSV($conn) {
        	handleExportCSV($conn);
    		}
		}

	// includes/funciones.php
	if (! function_exists('handleExportCSV')) {
    	function handleExportCSV($conn) {
        	verificarAdmin();
    		}
		}

	// // Validar que el usuario esté autenticado y tenga el rol adecuado
	if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') 
		{
    	error_log("❌ Intento de acceso sin permisos: " . $_SERVER['REMOTE_ADDR']);
    	header("Location: login.php");
    	exit();
		}

	// 3) 	Validar rol y registrar accesos no autorizados
	requireRole('admin', $conn);

	// 4) 	Registrar acceso al panel de administración
	registrarActividad($conn, $_SESSION['usuario'], ACCION_ACCESO_PANEL);

?>

<!DOCTYPE html>
	<html lang="es">
	<head>
		<meta charset="UTF-8" />
  		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  		<title>Panel Administración – Global Transportes</title>

  		<!-- Bootstrap 5 CSS -->
  		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>

		<!-- Font Awesome -->
  		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>

  		<!-- Tu CSS -->
  		<link rel="stylesheet" href="/../css/styles.css" /> 
		
		<?php require __DIR__ . '/../includes/header_panel.php'; ?>
	</head>

	<body class="d-flex flex-column min-vh-100 bg-light">
	
		<header class="bg-white shadow-sm py-3 mb-1 border-bottom">
  			<div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
    
    			<!-- Título del panel -->
    			<h1 class="h4 mb-2 mb-md-0 text-primary">
      				Panel de Administración
    			</h1>

    			<!-- Botones de acción -->
    			<div>
      				<a href="?exportar=csv" class="btn btn-success me-2" title="Exportar CSV">
        				<i class="fa fa-download me-1"></i> Exportar CSV
      				</a>
      				<a href="../logout.php" class="btn btn-outline-danger" title="Cerrar Sesión">
        				<i class="fa fa-sign-out-alt me-1"></i> Cerrar Sesión
      				</a>
    			</div>
  			</div>
		</header>


  		<!-- <main class="contenido"> -->
		<main class="container flex-fill py-4">
    	
			<!-- <section class="bienvenida"> -->
			<div class="card shadow-sm mb-2 border-0">
    			<div class="card-body">
        			<h5 class="card-title mb-3">
            			👋 Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?> (Admin)
        			</h5>
        			<p class="card-text">
            			Desde este panel podés gestionar usuarios, ver reportes y configurar opciones del sistema.
        			</p>
    			</div>
			</div>

			<section class="ventajas mb-5">
  				<h3 class="mb-1">Opciones disponibles</h3>
  				<div class="row g-4 cards">

  					<!-- Gestión de Usuarios -->
    				<div class="col-sm-6 col-lg-3">
    					<div class="card h-100 shadow-sm text-center">
    	    				<div class="card-body">
    	      					<i class="fa fa-users fa-2x text-primary mb-2"></i>
    	    					<h5 class="card-title">Gestión de Usuario</h5>
    	      					<p class="card-text">Crear, editar o eliminar cuentas.</p>
    	      					<a href="../admin/users/users.php" class="btn btn-primary">Ir</a>
    	    				</div>
    	  				</div>
					</div>
    	
					<!-- Auditoría -->
    				<div class="col-sm-6 col-lg-3">
    		    		<div class="card h-100 shadow-sm text-center">
    		    			<div class="card-body">
    		        			<i class="fa fa-clipboard-list fa-2x text-secondary mb-2"></i>
    		        			<h5 class="card-title">Auditoría</h5>
    		        			<p class="card-text">Ver registro de actividad.</p>
    		        			<a href="../admin/audits/historial_bd.php" class="btn btn-secondary">Ver</a>
    		      			</div>
    		    		</div>
					</div>
		
					<!-- Reportes -->
    				<div class="col-sm-6 col-lg-3">
    					<div class="card h-100 shadow-sm text-center">
    		    			<div class="card-body">
    							<i class="fa fa-chart-line fa-2x text-success mb-2"></i>
								<h5 class="card-title">Reportes</h5>
								<p class="card-text">Estadísticas y reportes del sistema.</p>    				<a href="?exportar=csv" class="btn btn-success">Exportar</a>
        		  			</div>
        				</div>
    				</div>

					<!-- ERP Dashboard -->
   	 				<div class="col-sm-6 col-lg-3">
    					<div class="card h-100 shadow-sm text-center">
    			    		<div class="card-body">
    							<i class="fa fa-rocket fa-2x text-info mb-2"></i>
    		    				<h5 class="card-title">ERP Dashboard</h5>
    	   	 					<p class="card-text">Acceso al módulo ERP completo.</p>
    	   	 					<a href="../views/dashboard/erp_dashboard.php" class="btn btn-info">Entrar</a>
    	      				</div>
    	    			</div>
    				</div>
  				</div>
			</section>

			<section class="row g-4">	
		
			</section>
		</main>
	</body>

	<footer class="bg-white text-center py-3 mt-auto">
    	<div class="container">
    		<small class="text-muted">&copy; 2025 Global Transportes. Todos los derechos reservados.</small>
    	</div>
  	</footer>
</html>
