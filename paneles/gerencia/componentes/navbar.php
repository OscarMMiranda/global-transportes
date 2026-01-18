<?php
if (!isset($_SESSION)) {
    session_start();
}

$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Gerencia';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="/paneles/router_panel.php">
            <i class="fa fa-user-tie me-2"></i> Panel Gerencia
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuGerencia">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuGerencia">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="/paneles/gerencia/controladores/panel_controlador.php">
                        <i class="fa fa-home me-1"></i> Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/dashboard/ejecutivo.php">
                        <i class="fa fa-chart-line me-1"></i> Dashboard Ejecutivo
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/reportes/estrategicos.php">
                        <i class="fa fa-chart-pie me-1"></i> Reportes Estratégicos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/finanzas/consolidadas.php">
                        <i class="fa fa-coins me-1"></i> Finanzas Consolidadas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/seguridad/auditoria/">
                        <i class="fa fa-clipboard-list me-1"></i> Auditoría
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