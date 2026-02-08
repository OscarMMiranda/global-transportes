<?php
    // archivo: /modulos/asistencias/modales/modal_vacaciones.php
?>

<!-- Modal: Registrar Vacaciones -->
<div class="modal fade" id="modalVacaciones" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Registrar Vacaciones</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="vac_conductor_id">

                <div class="mb-3">
                    <label class="form-label">Desde:</label>
                    <input type="date" id="vac_desde" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Hasta:</label>
                    <input type="date" id="vac_hasta" class="form-control">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" id="btnGuardarVacaciones" class="btn btn-primary">
                    Guardar
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
