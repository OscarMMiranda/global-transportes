<?php
// archivo: /modulos/mantenimiento/componentes/layout/header.php
?>

<header class="dashboard-header bg-white shadow-sm py-3 mb-4">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            <i class="fa-solid fa-screwdriver-wrench text-primary me-2 fs-4"></i>
            <h1 class="h5 m-0">Panel de Mantenimiento</h1>
        </div>

        <div>
            <span class="me-3 text-muted">
                <?= htmlspecialchars($_SESSION['usuario'] ?? 'Usuario') ?>
            </span>

            <a href="/logout.php" class="btn btn-outline-danger btn-sm">
                <i class="fa-solid fa-right-from-bracket"></i> Salir
            </a>
        </div>

    </div>
</header>
