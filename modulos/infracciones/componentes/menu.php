<?php
// archivo: /modulos/infracciones/componentes/menu.php
?>

<div class="mb-3">

    <!-- BOTÓN PRINCIPAL: LISTADO -->
    <a href="index.php" class="btn btn-primary">
        <i class="fa fa-list"></i> Listado de Infracciones
    </a>

    <!-- BOTÓN CREAR -->
    <button id="btnNuevoInfraccion" class="btn btn-success">
        <i class="fa fa-plus"></i> Nueva Infracción
    </button>

    <!-- BOTÓN ELIMINADAS (SOLO ADMIN) -->
    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 1) { ?>
        <a href="eliminadas.php" class="btn btn-danger">
            <i class="fa fa-trash"></i> Infracciones Eliminadas
        </a>
    <?php } ?>

</div>
