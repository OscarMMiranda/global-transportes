<?php
session_start();


// // Validar que el usuario esté autenticado y tenga el rol adecuado
	if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') 
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
  <title>Panel de Usuario</title>
  <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
  <main class="contenido">
    <section class="bienvenida">
      <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?> 👋</h2>
      <p>Has iniciado sesión correctamente.</p>
      <a href="logout.php" class="boton-accion">Cerrar Sesión</a>
    </section>
  </main>
</body>
</html>
