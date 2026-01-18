<?php
// archivo: /modulos/seguridad/permisos/componentes/panel_acciones.php
?>

<div class="columna bg-white p-3 rounded shadow-sm">

    <!-- Título -->
    <h2 class="h6 fw-semibold mb-3 text-dark">
        <i class="fa-solid fa-bolt me-2 text-primary"></i>
        Acciones
    </h2>

    <!-- Acciones rápidas -->
    <?php include __DIR__ . '/acciones_rapidas.php'; ?>

    <!-- Formulario permisos -->
    <form id="formPermisos">

        <div id="listaAcciones" class="mb-3"></div>

        <button
            type="submit"
            id="btnGuardar"
            class="btn btn-success btn-sm w-100"
        >
            <i class="fa-solid fa-floppy-disk me-1"></i>
            Guardar permisos
        </button>

    </form>

    <!-- Nueva acción -->
    <button
        id="btnNuevaAccion"
        class="btn btn-primary btn-sm mt-3 w-100"
    >
        <i class="fa-solid fa-plus me-1"></i>
        Nueva acción
    </button>

</div>
