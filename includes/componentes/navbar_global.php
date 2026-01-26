<?php
// archivo: /includes/componentes/navbar_global.php

$uri = $_SERVER['REQUEST_URI'];
function active($path) {
    global $uri;
    return (strpos($uri, $path) !== false) ? 'active fw-bold text-primary' : '';
}
?>

<nav class="navbar navbar-expand-lg bg-light border-bottom">
    <div class="container-fluid">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuERP">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuERP">

            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link <?= active('/vehiculos') ?> <?= active('/documentos_vehiculos') ?>"
                       href="/modulos/vehiculos/index.php">
                        <i class="fa fa-car-side me-1"></i> Vehículos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= active('/conductores') ?> <?= active('/documentos_conductores') ?>"
                       href="/modulos/conductores/index.php">
                        <i class="fa fa-id-card me-1"></i> Conductores
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= active('/empleados') ?> <?= active('/documentos_empleados') ?>"
                       href="/modulos/empleados/index.php">
                        <i class="fa fa-user-tie me-1"></i> Empleados
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= active('/empresas') ?> <?= active('/documentos_empresas') ?>"
                       href="/modulos/empresas/index.php">
                        <i class="fa fa-building me-1"></i> Empresas
                    </a>
                </li>

            </ul>

        </div>

    </div>
</nav>
