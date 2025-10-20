<!DOCTYPE html>
<html lang="es">
<head>
  	<meta charset="UTF-8"/>
  	<meta name="viewport" content="width=device-width,initial-scale=1"/>
  	<title>Gestión Agencias Aduanas</title>
  	
	<link
    	href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    	rel="stylesheet"
  	/>

	<!-- Bootstrap CSS -->
  	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  	<!-- FontAwesome -->
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   	<!-- Tus estilos personalizados -->
  	<link rel="stylesheet" href="/assets/css/estilos.css">


	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</head>

<body class="container mt-4">

  	<!-- INYECTAMOS BASE_URL PARA JS -->
  	<script>
    	// Calcula la ruta al módulo (sin backslashes)
    	window.BASE_URL = '<?= str_replace("\\", "/", dirname($_SERVER['SCRIPT_NAME'])) ?>';
    	console.log('BASE_URL =', BASE_URL);
  	</script>

  <h2 class="text-center mb-4">Gestión de Agencias Aduanas</h2>
  <div class="text-center mb-4">
    <a href="../../mantenimiento/mantenimiento.php" class="btn btn-outline-secondary">
      ← Volver a Mantenimiento
    </a>
  </div>
