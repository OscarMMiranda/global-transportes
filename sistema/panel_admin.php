<?php
session_start();
require_once '../includes/conexion.php';

// Mostrar errores en desarrollo (quitar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Validar que el usuario esté autenticado y tenga el rol adecuado
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
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
          <li><a href="../index.html" class="btn-nav">🏠 Inicio</a></li>
          <li><a href="logout.php" class="btn-nav">🔒 Cerrar Sesión</a></li>
          <li><a href="usuarios.php" class="btn-nav">👤 Gestionar Usuarios</a></li>
          <li><a href="historial_bd.php" class="btn-nav">📜 Auditoría</a></li>
          <li><a href="panel_admin.php?exportar=csv" class="btn-nav">📥 Exportar Reportes</a></li>
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
          <h4>📜 Auditoría</h4>
          <p>Ver registro de actividad de usuarios y administradores.</p>
          <a href="historial_bd.php" class="boton-accion">Ver Auditoría</a>
        </div>
        <div class="card">
          <h4>📊 Reportes</h4>
          <p>Visualizar estadísticas del sistema y exportar datos.</p>
          <a href="panel_admin.php?exportar=csv" class="boton-accion">Exportar Reporte</a>
        </div>
        <div class="card">
          <h4>🚀 Ingresar al ERP</h4>
          <p>Accedé al sistema de gestión completo.</p>
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
