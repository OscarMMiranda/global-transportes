<?php
// archivo: /modulos/asistencias/layout/header.php
?>

<header class="erp-header d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <div class="icon-wrapper me-3">
            <i class="fa fa-calendar-check"></i>
        </div>

        <div>
            <h5 class="m-0 fw-bold">Módulo de Asistencias</h5>
            <small class="text-muted">Control de asistencia del personal</small>
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">

        <!-- BOTÓN PARA VOLVER AL MÓDULO PRINCIPAL -->
        <a href="/paneles/admin/vistas/dashboard.php" class="btn btn-outline-light btn-sm">
            <i class="fa fa-home me-1"></i> Inicio
        </a>

        <span class="badge bg-light text-dark border d-none d-md-inline">
            <i class="fa fa-truck me-1"></i> ERP Global Transportes
        </span>
    </div>
</header>
