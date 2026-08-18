<?php
// archivo: /modulos/orden_trabajo/componentes/botones_superiores.php
?>

<div class="d-flex justify-content-end align-items-center mb-2" style="gap:6px;">

    <!-- VER -->
    <button class="btn btn-outline-info btn-sm px-2 py-1" onclick="abrirModalVer()">
        <i class="fa-solid fa-eye fa-xs"></i> Ver
    </button>

    <!-- CREAR -->
    <a href="/modulos/orden_trabajo/views/create.php" 
       class="btn btn-outline-primary btn-sm px-2 py-1">
        <i class="fa-solid fa-plus fa-xs"></i> Crear
    </a>

    <!-- ANULAR -->
    <button class="btn btn-outline-warning btn-sm px-2 py-1" onclick="abrirModalAnular()">
        <i class="fa-solid fa-ban fa-xs"></i> Anular
    </button>

    <!-- ELIMINAR -->
    <button class="btn btn-outline-danger btn-sm px-2 py-1" onclick="abrirModalEliminar()">
        <i class="fa-solid fa-trash fa-xs"></i> Eliminar
    </button>

</div>
