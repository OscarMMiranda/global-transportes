<?php
// archivo: /modulos/seguridad/permisos/sidebar.php
?>  


<div id="sidebar" class="sidebar bg-dark text-white">

    <div class="sidebar-header px-3 py-3">
        <h5 class="m-0">Permisos</h5>
    </div>

    <ul class="nav flex-column">

        <li class="nav-item">
            <a href="/modulos/usuarios/index.php" 
               class="nav-link text-white sidebar-link">
                <i class="fa-solid fa-user me-2"></i> Usuarios
            </a>
        </li>

        <li class="nav-item">
            <a href="/modulos/seguridad/roles/index.php" 
               class="nav-link text-white sidebar-link">
                <i class="fa-solid fa-users-gear me-2"></i> Roles
            </a>
        </li>

        <li class="nav-item">
            <a href="/modulos/seguridad/permisos/index.php" 
               class="nav-link text-white sidebar-link">
                <i class="fa-solid fa-key me-2"></i> Permisos por Rol
            </a>
        </li>

        <li class="nav-item">
            <a href="/modulos/seguridad/auditoria/index.php" 
               class="nav-link text-white sidebar-link">
                <i class="fa-solid fa-clipboard-list me-2"></i> Auditoría
            </a>
        </li>

    </ul>
</div>