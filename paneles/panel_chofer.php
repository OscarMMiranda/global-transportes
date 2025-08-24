<?php
session_start();

// // Validar que el usuario esté autenticado y tenga el rol adecuado
	if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'chofer') 
		{
    	error_log("❌ Intento de acceso sin permisos: " . $_SERVER['REMOTE_ADDR']);
    	header("Location: login.php");
    	exit();
		}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del Chofer - Global Transportes</title>
  <link rel="stylesheet" href="/../css/estilo.css">
</head>
<body>
  <header>
    <div class="contenedor">
      <div class="logo">
        <a href="/../index.php"><img src="../img/logo.png" alt="Logo Global Transportes" class="logo-img"></a>
      </div>
      <h1>Panel del Chofer</h1>
      <nav>
        <ul class="nav-menu">
          <li><a href="/../index.php" class="btn-nav">Inicio</a></li>
          <li><a href="/../logout.php" class="btn-nav">Cerrar Sesión</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="contenido">
    <section class="bienvenida">
      <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?> (Chofer)</h2>
      <p>Desde este panel podés ver tus asignaciones, rutas y horarios.</p>
    </section>

    <section class="ventajas">
      <h3>Opciones disponibles</h3>
      <div class="cards">
        <div class="card">
          <h4>🛣️ Mis Rutas</h4>
          <p>Visualizá las rutas asignadas para hoy.</p>
        </div>
        <div class="card">
          <h4>📅 Horarios</h4>
          <p>Revisá tus horarios y turnos.</p>
        </div>
        <div class="card">
          <h4>✉️ Contacto</h4>
          <p>Comunicáte con administración si necesitás ayuda.</p>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
  </footer>
</body>
</html>
