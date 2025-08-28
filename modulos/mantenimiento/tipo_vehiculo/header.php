<!DOCTYPE html>
<html lang="es">
	<head>
  		<meta charset="UTF-8">
  
  		<title><?= isset($titulo) ? $titulo : 'Mi App' ?></title>
  		
		<!-- Bootstrap 5 -->
  		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  		<!-- Font Awesome -->
  		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />

  		<!-- Estilos personalizados opcionales -->
  		<style>
    		body {
      			background-color: #f8f9fa;
    			}
    		.navbar-brand {
      			font-weight: bold;
      			font-size: 1.2rem;
    			}
    		.navbar {
      			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    			}
  		</style>
	</head>
	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-white px-3">
    		<div class="container-fluid">
      			<a href="../mantenimiento.php" class="btn btn-outline-primary">
        			<i class="fas fa-arrow-left"></i> Volver
      			</a>
      			<span class="navbar-brand ms-auto text-muted">
        			<?= isset($titulo) ? $titulo : 'Tipo de vehiculo' ?>
      			</span>
    		</div>
  		</nav>
  	<div class="container mt-4">
