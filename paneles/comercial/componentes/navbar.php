<?php
if (!isset($_SESSION)) {
    session_start();
}

$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Comercial';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="/paneles/router_panel.php">
            <i class="fa fa-briefcase me-2"></i> Panel Comercial
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuComercial">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuComercial">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="/paneles/comercial/controladores/panel_controlador.php">
                        <i class="fa fa-home me-1"></i> Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/clientes/">
                        <i class="fa fa-user-tie me-1"></i> Clientes
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/cotizaciones/">
                        <i class="fa fa-file-signature me-1"></i> Cotizaciones
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/ordenes/">
                        <i class="fa fa-file-invoice me-1"></i> Órdenes
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