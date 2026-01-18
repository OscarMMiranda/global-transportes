<?php
// archivo: /modulos/usuarios/componentes/header.php
// Componente: Header del módulo Usuarios
// Responsable: UI del módulo (sin lógica de negocio)


$idUsuario = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;
?>

<div id="usuarios-header" 
     class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom"
     style="border-color:#e5e5e5 !important;">

    <!-- Título -->
    <div class="d-flex flex-column">
        <h1 class="h4 mb-1 fw-bold d-flex align-items-center text-dark">
            <i class="fa fa-users me-2 text-primary"></i>
            Gestión de Usuarios
        </h1>
        <span class="text-muted small">Administración de cuentas, roles y estados</span>
    </div>

    <!-- Botones de acción -->
    <div class="d-flex align-items-center">

        <?php if (tienePermiso($idUsuario, 'usuarios', 'crear')): ?>
            <button id="btnCrearUsuario" class="btn btn-primary me-2 shadow-sm">
                <i class="fa fa-plus me-1"></i> Nuevo Usuario
            </button>
        <?php endif; ?>

        <?php if (tienePermiso($idUsuario, 'usuarios', 'exportar')): ?>
            <a href="/modulos/usuarios/acciones/exportar_csv.php" 
               class="btn btn-outline-success shadow-sm">
                <i class="fa fa-file-csv me-1"></i> Exportar CSV
            </a>
        <?php endif; ?>

        <?php if (
            !tienePermiso($idUsuario, 'usuarios', 'crear') &&
            !tienePermiso($idUsuario, 'usuarios', 'exportar')
        ): ?>
            <span class="text-muted small">Sin acciones disponibles</span>
        <?php endif; ?>

    </div>

</div>