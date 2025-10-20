<!-- archivo: /modulos/mantenimiento/componentes/layout/header.php -->

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mantenimiento de Datos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- ✅ Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- ✅ jQuery (necesario para DataTables y AJAX) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- ✅ Bootstrap JS (con Popper incluido) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Estilos personalizados opcionales -->
   <link rel="stylesheet" href="/modulos/mantenimiento/tipo_vehiculo/assets/estilos.css">
</head>
<body>

<header class="dashboard-header bg-white border-bottom shadow-sm py-2">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <!-- Título con icono -->
    <div class="d-flex align-items-center">
      <i class="fas fa-database text-primary me-2 fs-4"></i>
      <h1 class="h5 mb-0 fw-bold text-dark">Mantenimiento de Datos</h1>
    </div>

    <!-- Botón volver -->
    <a href="/modulos/erp_dashboard.php" class="btn btn-sm btn-primary d-flex align-items-center">
      <i class="fas fa-arrow-left me-2"></i> 
      <span>Volver al Dashboard</span>
    </a>
  </div>
</header>