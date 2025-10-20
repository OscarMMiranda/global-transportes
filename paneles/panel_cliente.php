<?php
session_start();

// Verificar que el usuario esté logueado y sea cliente
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'cliente') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del Cliente - Global Transportes</title>
  <link rel="stylesheet" href="/../css/estilo.css">
</head>
<body>
  <header>
    <div class="contenedor">
      <div class="logo">
        <a href="/../index.php"><img src="../img/logo.png" alt="Logo Global Transportes" class="logo-img"></a>
      </div>
      <h1>Panel del Cliente</h1>
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
      <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?> (Cliente)</h2>
      <p>Desde este panel podés hacer seguimiento de envíos, facturas y solicitar nuevos servicios.</p>
    </section>

    <section class="ventajas">
      <h3>Opciones disponibles</h3>
      <div class="cards">
        <div class="card">
          <h4>📦 Mis Envíos</h4>
          <p>Seguimiento en tiempo real de tus entregas.</p>
        </div>
        <div class="card">
          <h4>💳 Facturación</h4>
          <p>Revisá y descargá tus facturas.</p>
        </div>
        <div class="card">
          <h4>📝 Solicitar Servicio</h4>
          <p>Pedí un nuevo transporte de forma rápida.</p>
        </div>
      </div>
    </section>
  </main>

<?php require_once __DIR__ . '/../includes/footer_panel.php'; ?>

</body>
</html>
