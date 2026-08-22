<?php
// archivo: /modulos/orden_trabajo/componentes/header_erp.php

// ============================================================
//  SESIÓN Y VALIDACIÓN DE USUARIO
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: /login.php");
    exit;
}
?>

<!-- ============================================================
     BARRA SUPERIOR CORPORATIVA
============================================================ -->
<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <!-- Nombre del ERP -->
        <span class="navbar-brand fw-bold">
            ERP Global Transportes
        </span>

        <!-- Usuario + Logout -->
        <div class="d-flex align-items-center">
            <span class="text-white me-3">
                Bienvenido, <?= htmlspecialchars($_SESSION['usuario']); ?>
            </span>

            <a href="/sistema/logout.php" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-right-from-bracket"></i> Salir
            </a>
        </div>

    </div>
</nav>

<!-- ============================================================
     MENÚ ERP CORPORATIVO
============================================================ -->
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom shadow-sm">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand" href="/panel.php">
            <img src="/img/logo.png" width="150" alt="Logo ERP">
        </a>

        <!-- Botón responsive -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuERP">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menú -->
        <div class="collapse navbar-collapse" id="menuERP">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/erp_dashboard.php">
                        <i class="fa-solid fa-chart-line"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/vehiculos/vehiculos.php">
                        <i class="fa-solid fa-truck"></i> Vehículos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/clientes/clientes.php">
                        <i class="fa-solid fa-users"></i> Clientes
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/documentos/documentos.php">
                        <i class="fa-solid fa-file-lines"></i> Documentos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/empleados/empleados.php">
                        <i class="fa-solid fa-id-card"></i> Empleados
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/sistema/ayuda.php">
                        <i class="fa-solid fa-circle-question"></i> Ayuda
                    </a>
                </li>

            </ul>
        </div>

    </div>
</nav>
