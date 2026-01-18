<?php
// archivo: /includes/componentes/header_global.php
// Barra superior global del sistema (versión moderna)
?>

<nav class="navbar navbar-expand-lg bg-white shadow-sm border-bottom">
    <div class="container-fluid">

        <!-- Logo + Nombre -->
        <a href="/paneles/panel_admin.php" class="navbar-brand d-flex align-items-center">
            <i class="fa fa-truck text-primary me-2 fs-4"></i>
            <span class="fw-bold text-dark">Global Transportes</span>
        </a>

        <!-- Botón móvil -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarGlobal">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarGlobal">

            <!-- Botón Volver -->
            <a href="javascript:history.back()" class="btn btn-light border me-3 ms-lg-3">
                <i class="fa fa-arrow-left me-1"></i> Volver
            </a>

            <div class="ms-auto d-flex align-items-center gap-3">

                <!-- Usuario -->
                <div class="d-flex align-items-center text-dark fw-semibold">
                    <i class="fa fa-user-circle text-primary me-2 fs-5"></i>
                    <?php
                        if (isset($_SESSION['usuario'])) {
                            echo htmlspecialchars($_SESSION['usuario']);
                        } else {
                            echo 'Usuario';
                        }
                    ?>
                </div>

                <!-- Salir -->
                <a href="/logout.php" class="btn btn-outline-danger">
                    <i class="fa fa-sign-out-alt me-1"></i> Salir
                </a>

            </div>

        </div>
    </div>
</nav>