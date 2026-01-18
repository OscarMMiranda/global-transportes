<?php
if (!isset($_SESSION)) {
    session_start();
}

$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'RRHH';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="/paneles/router_panel.php">
            <i class="fa fa-user-group me-2"></i> Panel RRHH
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuRRHH">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuRRHH">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="/paneles/rrhh/controladores/panel_controlador.php">
                        <i class="fa fa-home me-1"></i> Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/personal/">
                        <i class="fa fa-users me-1"></i> Personal
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/asistencia/">
                        <i class="fa fa-calendar-check me-1"></i> Asistencia
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/contratos/">
                        <i class="fa fa-file-contract me-1"></i> Contratos
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