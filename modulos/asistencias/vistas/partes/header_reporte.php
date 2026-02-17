<?php
// archivo: modulos/asistencias/vistas/partes/header_reporte.php

if (!isset($titulo)) {
    $titulo = "Reporte Diario de Asistencias";
}

if (!isset($subtitulo)) {
    $subtitulo = date("d/m/Y");
}

if (!isset($icono)) {
    $icono = "fa-calendar-day";
}
?>

<div class="card shadow-sm mb-1">

    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

        <div>
            <h5 class="mb-0">
                <i class="fa fa-calendar-day me-2"></i>
                <?php echo htmlspecialchars($titulo); ?>
            </h5>

            <small class="text-white-50">
                <?php echo htmlspecialchars($subtitulo); ?>
            </small>
        </div>

        <div>
            <button class="btn btn-sm btn-light" onclick="location.reload()">
                <i class="fa fa-refresh"></i> Actualizar
            </button>
        </div>

    </div>

</div>
