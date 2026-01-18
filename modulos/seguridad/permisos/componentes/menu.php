<?php
// archivo: /modulos/seguridad/permisos/componentes/menu.php
?>

<div class="menu-permisos mb-3 d-flex gap-2">

    <a href="dashboard.php" class="btn btn-outline-primary">
        <i class="fa-solid fa-chart-pie"></i> Dashboard
    </a>

    <a href="index.php" class="btn btn-outline-secondary">
        <i class="fa-solid fa-shield-halved"></i> Administrar permisos
    </a>

    <a href="ver_rol.php?rol_id=1" class="btn btn-outline-success">
        <i class="fa-solid fa-user-shield"></i> Ver permisos por rol
    </a>

    <a href="ver_modulo.php?modulo_id=1" class="btn btn-outline-warning">
        <i class="fa-solid fa-box"></i> Ver permisos por módulo
    </a>

    <a href="exportar_permisos.php?tipo=json" class="btn btn-outline-dark">
        <i class="fa-solid fa-file-export"></i> Exportar permisos
    </a>

</div>