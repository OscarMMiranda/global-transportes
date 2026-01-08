<?php
// archivo: /admin/panel_admin.php

session_start();
require_once __DIR__ . '/../includes/config.php';
$conn = getConnection();

// Depuración defensiva
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log_panel.txt');
error_reporting(E_ALL);

// Validar sesión y rol
require_once '../includes/helpers.php';
require_once '../includes/funciones.php';
requireRole('admin', $conn);

// Registrar acceso al panel
registrarActividad($conn, $_SESSION['usuario'], 'Acceso al Panel de Administración');
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <title>Panel Administración – Global Transportes</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="/../css/styles.css" />

  <?php require __DIR__ . '/../includes/header_panel.php'; ?>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

  <!-- Encabezado -->
  <header class="bg-white shadow-sm py-3 mb-1 border-bottom">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
      <h1 class="h4 mb-2 mb-md-0 text-primary">Panel de Administración</h1>
      <div>
        <a href="?exportar=csv" class="btn btn-success me-2">
          <i class="fa fa-download me-1"></i> Exportar CSV
        </a>
        <a href="/index.php" class="btn btn-outline-secondary me-2">
          <i class="fas fa-arrow-left me-1"></i> Volver al sitio
        </a>
        <a href="../logout.php" class="btn btn-outline-danger">
          <i class="fa fa-sign-out-alt me-1"></i> Cerrar Sesión
        </a>
      </div>
    </div>
  </header>

  <!-- Contenido principal -->
  <main class="container flex-fill py-4">

    <!-- Bienvenida -->
    <div class="card shadow-sm mb-4 border-0">
      <div class="card-body">
        <h5 class="card-title mb-3">👋 Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?> (Admin)</h5>
        <p class="card-text">Desde este panel podés gestionar usuarios, ver reportes y configurar opciones del sistema.</p>
      </div>
    </div>

    <!-- Tarjetas de navegación -->
    <section class="ventajas mb-5">
      <h3 class="mb-3">Opciones disponibles</h3>
      <div class="row g-4">

        <!-- Gestión de Usuarios -->
        <div class="col-sm-6 col-lg-3">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="fa fa-users fa-2x text-primary mb-2"></i>
              <h5 class="card-title">Gestión de Usuarios</h5>
              <p class="card-text">Crear, editar o eliminar cuentas.</p>
              <a href="/modulos/usuarios/index.php" class="btn btn-primary">Ir</a>
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
              <a href="audits/historial_bd.php" class="btn btn-secondary">Ver</a>
            </div>
          </div>
        </div>

        <!-- Reportes -->
        <div class="col-sm-6 col-lg-3">
          <div class="card h-100 shadow-sm text-center">
            <div class="card-body">
              <i class="fa fa-chart-line fa-2x text-success mb-2"></i>
              <h5 class="card-title">Reportes</h5>
              <p class="card-text">Estadísticas del sistema.</p>
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
				<a href="../../modulos/erp_dashboard.php" class="btn-nav">⬅️ Volver al Panel</a>
	            </div>
          </div>
        </div>

      </div>
    </section>

  </main>

  <?php require __DIR__ . '/../includes/footer_panel.php'; ?>
</body>
</html>