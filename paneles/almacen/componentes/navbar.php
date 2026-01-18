<?php
if (!isset($_SESSION)) {
    session_start();
}

$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Logística';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="/paneles/router_panel.php">
            <i class="fa fa-warehouse me-2"></i> Panel Logística
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuLogistica">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuLogistica">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="/paneles/logistica/controladores/panel_controlador.php">
                        <i class="fa fa-home me-1"></i> Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/inventario/">
                        <i class="fa fa-boxes me-1"></i> Inventario
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/cargas/">
                        <i class="fa fa-dolly me-1"></i> Cargas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/despachos/">
                        <i class="fa fa-truck-loading me-1"></i> Despachos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/seguimiento/">
                        <i class="fa fa-map-location-dot me-1"></i> Seguimiento
                    </a>
                </li>

            </ul>

            <div class="d-flex align-items-center text-white">
                <span class="me-3">
                    <i class="fa fa-user-circle me-1"></i>
                    <?= htmlspecialchars($usuario) ?>
                </span>

                <a href="/logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fa fa-sign-out-alt me-1"></i> Salir
                </a>
            </div>

        </div>
    </div>
</nav>