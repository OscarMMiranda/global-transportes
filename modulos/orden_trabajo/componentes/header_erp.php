<?php
// archivo: /modulos/orden_trabajo/componentes/header_erp.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: /login.php");
    exit;
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid d-flex justify-content-between">
        <span class="navbar-brand mb-0 h1">ERP Global Transportes</span>
        <div>
            <span class="text-white me-3">
                Bienvenido, <?= htmlspecialchars($_SESSION['usuario']); ?>
            </span>
            <a href="/sistema/logout.php" class="btn btn-danger btn-sm">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="/panel.php">
            <img src="/img/logo.png" width="150">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuERP">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuERP">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/modulos/erp_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/modulos/vehiculos/vehiculos.php">Vehículos</a></li>
                <li class="nav-item"><a class="nav-link" href="/modulos/clientes/clientes.php">Clientes</a></li>
                <li class="nav-item"><a class="nav-link" href="/modulos/documentos/documentos.php">Documentos</a></li>
                <li class="nav-item"><a class="nav-link" href="/modulos/empleados/empleados.php">Empleados</a></li>
                <li class="nav-item"><a class="nav-link" href="/sistema/ayuda.php">Ayuda</a></li>
            </ul>
        </div>
    </div>
</nav>
