<?php
// archivo: /modulos/asistencias/modales/modal_historial_asistencia.php
?>

<div class="modal fade" id="modalHistorialAsistencia" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Historial de asistencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="contenedorHistorial">
                <div class="text-center p-4">
                    <div class="spinner-border"></div>
                    <p>Cargando historial...</p>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>
