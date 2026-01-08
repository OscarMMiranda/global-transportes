<?php
// archivo: /modulos/usuarios/componentes/header.php
?>
   
<div class="d-flex justify-content-between align-items-center mb-3">

    <!-- Título -->
    <h1 class="h3 mb-0 d-flex align-items-center">
        <i class="fa fa-users me-2 text-primary"></i>
        Gestión de Usuarios
    </h1>

    <!-- Botones de acción -->
    <div>

        <?php if (tienePermiso($_SESSION['usuario_id'], 'usuarios', 'crear')): ?>
            <!-- Botón que abrirá el modal desde JS -->
            <button id="btnCrearUsuario" class="btn btn-primary me-2">
                <i class="fa fa-plus me-1"></i> Crear Usuario
            </button>
        <?php endif; ?>

        <?php if (tienePermiso($_SESSION['usuario_id'], 'usuarios', 'exportar')): ?>
            <a href="/modulos/usuarios/acciones/exportar_csv.php" 
               class="btn btn-success">
                <i class="fa fa-file-csv me-1"></i> Exportar CSV
            </a>
        <?php endif; ?>

    </div>

</div>