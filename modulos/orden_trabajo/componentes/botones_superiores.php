<?php
// archivo: /modulos/orden_trabajo/componentes/botones_superiores.php
?>

<div class="d-flex justify-content-end flex-wrap gap-2 mb-3">

    <a href="/modulos/orden_trabajo/views/create.php" class="btn btn-outline-primary btn-sm">
        <i class="fa-solid fa-plus"></i> Crear OT
    </a>

    <button class="btn btn-outline-warning btn-sm" onclick="abrirModalAnular()">
        <i class="fa-solid fa-ban"></i> Anular
    </button>

    <button class="btn btn-outline-danger btn-sm" onclick="abrirModalEliminar()">
        <i class="fa-solid fa-trash"></i> Eliminar
    </button>

</div>
