<?php
	session_start();
	require_once '../includes/conexion.php';

	// Mostrar errores en desarrollo (quitar en producción)
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	// Validar que el usuario esté autenticado y tenga el rol adecuado
	if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') 
		{
    	error_log("❌ Intento de acceso sin permisos: " . $_SERVER['REMOTE_ADDR']);
    	header("Location: login.php");
    	exit();
		}

	// Registrar actividad en historial_bd
	$usuario = $_SESSION['usuario'];
	$accion = "Accedió al panel de administración";
	$ip_usuario = $_SERVER['REMOTE_ADDR'];
	$sql_historial = "INSERT INTO historial_bd (usuario, accion, ip_usuario) VALUES ('$usuario', '$accion', '$ip_usuario')";
	$conn->query($sql_historial);

	// 4) Incluir layout principal
	//  require __DIR__ . '/partials/header.php';
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
  		<!-- <link rel="stylesheet" href="../css/estilo.css" /> -->
		<?php require __DIR__ . '/partials/header.php'; ?>
	</head>

	<body class="bg-light">
		<header>
  			<div class="contenedor">
    			<h1>Panel de Administración</h1>   			
				<!-- <a class="navbar-brand" href="../index.html">
      				<img src="../img/logo.png" alt="Logo" width="32" class="d-inline-block align-text-top">
      				Global Transportes
    			</a> -->
				<div>
    				<a href="?exportar=csv"      class="btn btn-success me-2">
						<i class="fa fa-download me-1">
						</i> Exportar CSV
					</a>
    				<a href="logout.php"         class="btn btn-outline-danger">
						<i class="fa fa-sign-out-alt me-1">
						</i> Cerrar Sesión
					</a>
    			</div>					
  			</div>
		</header>

  		<!-- <main class="contenido"> -->
		<main class="container flex-fill py-2">
    	
			<!-- <section class="bienvenida"> -->
			<div class="d-flex justify-content-between align-items-center mb-4">
    			<!-- <h1 class="h3 mb-0">Panel de Administración</h1> -->
    		
				<nav>
					<section class="bienvenida mb-5">
    					<h3>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?> (Admin)</h3>
    					<p>Desde este panel podés gestionar usuarios, ver reportes y configurar opciones del sistema.
						</p>
  					</section>	
				</nav>	
  			</div>


<section class="ventajas mb-5">
  <h3 class="mb-4">Opciones disponibles</h3>
  <div class="row g-4 cards">
    <div class="col-sm-6 col-lg-3">
    	<div class="card h-100 shadow-sm text-center">
        	<div class="card-body">
          		<i class="fa fa-users fa-2x text-primary mb-2"></i>
        		<h5 class="card-title">Gestión de Usuarios</h5>
          		<p class="card-text">Crear, editar o eliminar cuentas.</p>
          		<a href="usuarios.php" class="btn btn-primary">Ir</a>
        	</div>
      	</div>
	</div>
    	<!-- Repite para las otras 3 tarjetas -->
 		<!-- Auditoría -->
    			<div class="col-sm-6 col-lg-3">
        			<div class="card h-100 shadow-sm text-center">
        				<div class="card-body">
            				<i class="fa fa-clipboard-list fa-2x text-secondary mb-2"></i>
            				<h5 class="card-title">Auditoría</h5>
            				<p class="card-text">Ver registro de actividad.</p>
            				<a href="historial_bd.php" class="btn btn-secondary">Ver</a>
          				</div>
        			</div>
      			</div>
		
		<!-- Reportes -->
    			<div class="col-sm-6 col-lg-3">
        			<div class="card h-100 shadow-sm text-center">
          				<div class="card-body">
            				<i class="fa fa-chart-line fa-2x text-success mb-2"></i>
            				<h5 class="card-title">Reportes</h5>
            				<p class="card-text">Estadísticas y reportes del sistema.</p>
            				<a href="?exportar=csv" class="btn btn-success">Exportar</a>
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
            				<a href="../modulos/erp_dashboard.php" class="btn btn-info">Entrar</a>
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
