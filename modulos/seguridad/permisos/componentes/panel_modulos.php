<?php
// archivo: /modulos/seguridad/permisos/componentes/panel_modulos.php
?>

<div class="columna bg-white p-3 rounded shadow-sm">

    <!-- Título -->
    <h2 class="h6 fw-semibold mb-3 text-dark">
        <i class="fa-solid fa-layer-group me-2 text-primary"></i>
        Módulos
    </h2>

    <!-- Buscador -->
    <div class="mb-2">
        <input
            type="text"
            id="buscarModulo"
            class="form-control form-control-sm"
            placeholder="Buscar módulo..."
            aria-label="Buscar módulo"
        >
    </div>

    <!-- Lista -->
    <ul id="listaModulos" class="list-group list-group-flush"></ul>

    <!-- Acción -->
    <button id="btnNuevoModulo" class="btn btn-primary btn-sm mt-3 w-100">
        <i class="fa-solid fa-plus me-1"></i>
        Nuevo módulo
    </button>

</div>
