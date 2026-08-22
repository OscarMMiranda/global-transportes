<?php
// archivo: /modulos/orden_trabajo/componentes/botones_superiores.php
?>

<div class="d-flex justify-content-end align-items-center mb-2" style="gap:8px;">

    <!-- CREAR -->
    <a href="/modulos/orden_trabajo/views/create.php" 
       class="btn btn-outline-primary btn-sm px-3 py-1">
        <i class="fa-solid fa-plus"></i> Crear
    </a>

    <!-- ANULAR GLOBAL -->
    <button class="btn btn-outline-warning btn-sm px-3 py-1"
            onclick="abrirModalAnular()">
        <i class="fa-solid fa-ban"></i> Anular
    </button>

    <!-- ELIMINAR GLOBAL -->
    <button class="btn btn-outline-danger btn-sm px-3 py-1"
            onclick="abrirModalEliminar()">
        <i class="fa-solid fa-trash"></i> Eliminar
    </button>

</div>
