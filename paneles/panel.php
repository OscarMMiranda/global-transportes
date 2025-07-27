<?php
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
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
