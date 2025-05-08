<?php
session_start();

if (
    !isset($_SESSION['usuario']) ||
    !isset($_SESSION['rol_nombre']) ||
    $_SESSION['rol_nombre'] !== 'admin'
) {
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
  <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
  <header>
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
        </ul>
      </nav>
    </div>
  </header>

  <main class="contenido">
    <section class="bienvenida">
      <h2>Bienvenido al ERP, <?= htmlspecialchars($_SESSION['usuario']) ?></h2>
      <p>Accedé a la gestión completa de tu empresa de transporte.</p>
    </section>

    <section class="ventajas">
      <h3>Módulos disponibles</h3>
      <div class="cards">
        <div class="card">
          <h4>👥 Clientes</h4>
          <p>Gestioná tus clientes.</p>
          <a href="clientes/clientes.php" class="boton-accion">Ir</a>
        </div>
        <div class="card">
          <h4>🧑‍💼 Empleados</h4>
          <p>Ver o registrar personal de la empresa.</p>
          <a href="empleados/empleados.php" class="boton-accion">Ir</a>
        </div>
        <div class="card">
          <h4>🚛 Vehículos</h4>
          <p>Registrar y controlar la flota de transporte.</p>
          <a href="vehiculos/vehiculos.php" class="boton-accion">Ir</a>
        </div>
        <div class="card">
          <h4>📍 Destinos</h4>
          <p>Administrar rutas y zonas de entrega.</p>
          <a href="destinos/destinos.php" class="boton-accion">Ir</a>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
  </footer>
</body>
</html>

