<?php
if (!isset($_SESSION)) {
    session_start();
}

$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Operaciones';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-info shadow-sm">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="/paneles/router_panel.php">
            <i class="fa fa-gears me-2"></i> Panel Operaciones
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuOperaciones">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuOperaciones">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="/paneles/operaciones/controladores/panel_controlador.php">
                        <i class="fa fa-home me-1"></i> Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/flota/control_flota.php">
                        <i class="fa fa-truck-moving me-1"></i> Control de Flota
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/rutas/asignacion.php">
                        <i class="fa fa-route me-1"></i> Asignación de Rutas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/cargas/control.php">
                        <i class="fa fa-boxes-stacked me-1"></i> Control de Cargas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/incidencias/">
                        <i class="fa fa-triangle-exclamation me-1"></i> Incidencias
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