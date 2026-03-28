<?php
	// archivo: /modulos/mantenimiento/tipo_destinos/componentes/tabs_contenido.php
	// Componente: Contenido de tabs (activos e inactivos)
?>

<div class="tab-content" style="margin-top: 15px;">

    <!-- TABLA ACTIVOS -->
    <div class="tab-pane fade in active" id="activos">
        <?php include __DIR__ . '/tabla_activos.php'; ?>
    </div>

    <!-- TABLA INACTIVOS -->
    <div class="tab-pane fade" id="inactivos">
        <?php include __DIR__ . '/../panel_inactivos.php'; ?>
    </div>

</div>
