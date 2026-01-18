<?php
if (!isset($_SESSION)) {
    session_start();
}

$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Finanzas';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="/paneles/router_panel.php">
            <i class="fa fa-coins me-2"></i> Panel Finanzas
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuFinanzas">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuFinanzas">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="/paneles/finanzas/controladores/panel_controlador.php">
                        <i class="fa fa-home me-1"></i> Inicio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/facturacion/">
                        <i class="fa fa-file-invoice-dollar me-1"></i> Facturación
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/cuentas/cobrar.php">
                        <i class="fa fa-hand-holding-dollar me-1"></i> Cuentas por Cobrar
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/cuentas/pagar.php">
                        <i class="fa fa-money-bill-transfer me-1"></i> Cuentas por Pagar
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/modulos/reportes/finanzas.php">
                        <i class="fa fa-chart-pie me-1"></i> Reportes
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