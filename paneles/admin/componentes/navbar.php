<?php
// archivo: /paneles/admin/componentes/navbar.php

if (!isset($_SESSION)) {
    session_start();
}

$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuario';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">

        <!-- Logo / Título -->
        <a class="navbar-brand fw-bold" href="/paneles/router_panel.php">
            <i class="fa fa-shield-alt me-2"></i> Panel Admin
        </a>

        <!-- Botón responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPanel">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menú -->
        <div class="collapse navbar-collapse" id="menuPanel">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!-- Inicio -->
                <li class="nav-item">
                    <a class="nav-link" href="/paneles/admin/controladores/panel_controlador.php">
                        <i class="fa fa-home me-1"></i> Inicio
                    </a>
                </li>

                <!-- Usuarios -->
                <li class="nav-item">
                    <a class="nav-link" href="/modulos/usuarios/">
                        <i class="fa fa-users me-1"></i> Usuarios
                    </a>
                </li>

                <!-- Auditoría -->
                <li class="nav-item">
                    <a class="nav-link" href="/modulos/seguridad/auditoria/">
                        <i class="fa fa-clipboard-list me-1"></i> Auditoría
                    </a>
                </li>

                <!-- Reportes -->
                <li class="nav-item">
                    <a class="nav-link" href="/paneles/admin/acciones/exportar_csv.php">
                        <i class="fa fa-chart-line me-1"></i> Reportes
                    </a>
                </li>

            </ul>

            <!-- Usuario + Logout -->
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