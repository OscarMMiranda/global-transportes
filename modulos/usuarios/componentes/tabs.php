<?php
// archivo: /modulos/usuarios/componentes/tabs.php
// --------------------------------------------------------------
// Componente: Tabs del módulo Usuarios
// Responsable: UI de filtrado por estado
// No contiene lógica de negocio
// Requiere Bootstrap 5
// --------------------------------------------------------------
?>
<?php
$idUsuario = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;
?>

<div id="usuarios-tabs" class="mb-1">

    <ul class="nav nav-tabs filtro-estado" role="tablist" data-modulo="usuarios">

        <li class="nav-item">
            <a class="nav-link active px-4 py-2" href="#" data-estado="0">
                <i class="fa fa-check-circle me-1 text-success"></i>
                Activos
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link px-4 py-2" href="#" data-estado="1">
                <i class="fa fa-ban me-1 text-secondary"></i>
                Inactivos
            </a>
        </li>

    </ul>

</div>