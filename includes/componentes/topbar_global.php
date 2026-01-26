<?php
// archivo: /includes/componentes/topbar_global.php 
?>

<nav class="navbar navbar-expand-lg bg-white shadow-sm border-bottom">
    <div class="container-fluid">

        <a href="/paneles/panel_admin.php" class="navbar-brand d-flex align-items-center">
            <i class="fa fa-truck text-primary me-2 fs-4"></i>
            <span class="fw-bold text-dark">Global Transportes</span>
        </a>

        <div class="ms-auto d-flex align-items-center gap-3">

            <a href="javascript:history.back()" class="btn btn-light border">
                <i class="fa fa-arrow-left me-1"></i> Volver
            </a>

            <div class="d-flex align-items-center text-dark fw-semibold">
                <i class="fa fa-user-circle text-primary me-2 fs-5"></i>
                <?= htmlspecialchars($_SESSION['usuario']); ?>
            </div>

            <a href="/sistema/logout.php" class="btn btn-outline-danger">
                <i class="fa fa-sign-out-alt me-1"></i> Salir
            </a>

        </div>

    </div>
</nav>
