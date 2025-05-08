<?php
session_start();

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: ../sistema/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>ERP - Global Transportes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/base.css"> <!-- Estilos globales -->
  <link rel="stylesheet" href="../css/dashboard.css"> <!-- Estilos específicos del dashboard -->
</head>
<body>
  <header class="dashboard-header">
    <div class="contenedor">
      <div class="logo">
        <a href="../index.html">
          <img src="../img/logo.png" alt="Logo Global Transportes" class="logo-img">
        </a>
      </div>
      <h1>ERP Global Transportes</h1>
      <nav>
        <ul class="nav-menu">
          <li><a href="../index.html" class="btn-nav">Inicio</a></li>
          <li><a href="../sistema/logout.php" class="btn-nav">Cerrar Sesión</a></li>
          <li><a href="../sistema/panel_admin.php" class="btn-nav">Volver al Panel Admin</a></li>
          <li><a href="../documentos/documentos.php" class="btn-nav">📄 Documentos</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="dashboard-container">
    <section class="bienvenida">
      <h2>Bienvenido al ERP, <?= htmlspecialchars($_SESSION['usuario']) ?></h2>
      <p>Accede a la gestión completa de tu empresa de transporte.</p>
    </section>

    <section class="dashboard-cards">
      <div class="card-dashboard">
        <h4>👥 Clientes</h4>
        <p>Gestiona tus clientes.</p>
        <a href="clientes/clientes.php" class="dashboard-btn">Ir</a>
      </div>
      <div class="card-dashboard">
        <h4>🧑‍💼 Empleados</h4>
        <p>Ver o registrar personal de la empresa.</p>
        <a href="empleados/empleados.php" class="dashboard-btn">Ir</a>
      </div>
      <div class="card-dashboard">
        <h4>🚛 Vehículos</h4>
        <p>Registrar y controlar la flota de transporte.</p>
        <a href="vehiculos/vehiculos.php" class="dashboard-btn">Ir</a>
      </div>
      <div class="card-dashboard">
        <h4>📍 Destinos</h4>
        <p>Administrar rutas y zonas de entrega.</p>
        <a href="destinos/destinos.php" class="dashboard-btn">Ir</a>
      </div>
      <div class="card-dashboard">
        <h4>📄 Documentos Vehiculares</h4>
        <p>Registrar y administrar documentos de cada vehículo.</p>
        <a href="documentos/form_documentos.php" class="dashboard-btn">Ir</a>
      </div>
    </section>
  </main>

  <footer class="footer">
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
  </footer>
</body>
</html>


