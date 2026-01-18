<?php
// archivo: /modulos/seguridad/usuarios/componentes/panel_lista_usuarios.php
// Panel lateral: lista de usuarios
?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white py-2">
        <strong><i class="fa-solid fa-list"></i> Lista de Usuarios</strong>
    </div>

    <div class="card-body p-2">

        <!-- Contenedor donde se cargará la tabla vía AJAX -->
        <div id="lista-usuarios">
            <div class="text-center text-muted py-4">
                <i class="fa-solid fa-spinner fa-spin"></i> Cargando usuarios...
            </div>
        </div>

    </div>
</div>