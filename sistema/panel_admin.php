<?php
session_start();

// Mostrar errores en desarrollo (quitar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Validar que el usuario esté autenticado y tenga el rol adecuado
if (
    !isset($_SESSION['usuario']) ||
    !isset($_SESSION['rol_nombre']) ||
    $_SESSION['rol_nombre'] !== 'admin'
) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración - Global Transportes</title>
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
      <h1>Panel de Administración</h1>
      <nav>
        <ul class="nav-menu">
          <li><a href="../index.html" class="btn-nav">Inicio</a></li>
          <li><a href="logout.php" class="btn-nav">Cerrar Sesión</a></li>
          <li><a href="usuarios.php" class="btn-nav">Gestionar Usuarios</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="contenido">
    <section class="bienvenida">
      <h2>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?> (Admin)</h2>
      <p>Desde este panel podés gestionar usuarios, ver reportes y configurar opciones del sistema.</p>
    </section>

    <section class="ventajas">
      <h3>Opciones disponibles</h3>
      <div class="cards">
        <div class="card">
          <h4>👤 Gestión de Usuarios</h4>
          <p>Agregar, editar o eliminar cuentas de usuario.</p>
          <a href="usuarios.php" class="boton-accion">Ir</a>
        </div>
        <div class="card">
          <h4>📊 Reportes</h4>
          <p>Visualizá estadísticas del sistema y actividad reciente.</p>
          <a href="#" class="boton-accion">Próximamente</a>
        </div>
        <div class="card">
          <h4>⚙️ Configuración</h4>
          <p>Personalizá la plataforma según tus necesidades.</p>
          <a href="#" class="boton-accion">Próximamente</a>
        </div>
        <div class="card">
          <h4>🚀 Ingresar al ERP</h4>
          <p>Accedé al sistema de gestión (ERP) completo.</p>
          <a href="../modulos/erp_dashboard.php" class="boton-accion">Entrar</a>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
  </footer>
</body>
</html>
