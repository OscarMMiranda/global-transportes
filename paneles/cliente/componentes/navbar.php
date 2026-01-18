<?php
if (!isset($_SESSION)) {
    session_start();
}

$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Cliente';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="/paneles/router_panel.php">
            <i class="fa fa-user-tag me-2"></i> Panel Cliente
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuCliente">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuCliente">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="/paneles/cliente/controladores/panel_controlador.php">
                        <i class="fa fa-home me-1"></i> Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/envios/mis_envios.php">
                        <i class="fa fa-box me-1"></i> Mis Envíos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/facturacion/">
                        <i class="fa fa-file-invoice-dollar me-1"></i> Facturación
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