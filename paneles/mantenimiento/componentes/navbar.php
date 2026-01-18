<?php
// archivo: /paneles/mantenimiento/componentes/navbar.php

if (!isset($_SESSION)) {
    session_start();
}

$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Mantenimiento';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-warning shadow-sm">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="/paneles/router_panel.php">
            <i class="fa fa-tools me-2"></i> Panel Mantenimiento
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuMantenimiento">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuMantenimiento">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="/paneles/mantenimiento/controladores/panel_controlador.php">
                        <i class="fa fa-home me-1"></i> Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/unidades/">
                        <i class="fa fa-truck me-1"></i> Unidades
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/mantenimiento/preventivo.php">
                        <i class="fa fa-tools me-1"></i> Preventivo
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/mantenimiento/correctivo.php">
                        <i class="fa fa-wrench me-1"></i> Correctivo
                    </a>
                </li>

            </ul>

            <div class="d-flex align-items-center text-dark">
                <span class="me-3 fw-bold">
                    <i class="fa fa-user-circle me-1"></i>
                    <?= htmlspecialchars($usuario) ?>
                </span>

                <a href="/logout.php" class="btn btn-dark btn-sm">
                    <i class="fa fa-sign-out-alt me-1"></i> Salir
                </a>
            </div>

        </div>
    </div>
</nav>