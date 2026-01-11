  <?php
// archivo: /includes/navbar.php

// Detectar ruta actual para resaltar el menú activo
$uri = $_SERVER['REQUEST_URI'];

function active($path) {
    global $uri;
    return (strpos($uri, $path) !== false) ? 'active' : '';
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">

    <a class="navbar-brand fw-bold" href="/modulos/erp_dashboard.php">
      <i class="fa-solid fa-layer-group me-2"></i> ERP
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarERP">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarERP">

      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link <?= active('/erp_dashboard') ?>" href="/modulos/erp_dashboard.php">
            <i class="fa-solid fa-gauge me-1"></i> Dashboard
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= active('/conductores') ?>" href="/modulos/conductores/index.php">
            <i class="fa-solid fa-id-card me-1"></i> Conductores
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= active('/vehiculos') ?>" href="/modulos/vehiculos/index.php">
            <i class="fa-solid fa-car-side me-1"></i> Vehículos
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= active('/usuarios') ?>" href="/modulos/usuarios/index.php">
            <i class="fa-solid fa-users me-1"></i> Usuarios
          </a>
        </li>

      </ul>

      <!-- Lado derecho -->
      <ul class="navbar-nav ms-auto">

        <li class="nav-item">
          <a class="nav-link text-warning" href="/logout.php">
            <i class="fa-solid fa-right-from-bracket me-1"></i> Salir
          </a>
        </li>

      </ul>

    </div>
  </div>
</nav>