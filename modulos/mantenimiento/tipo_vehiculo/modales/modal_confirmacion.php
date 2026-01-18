<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/modales/modal_confirmacion.php
?>

<div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="tituloConfirmacion">
                    Confirmar acción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p id="mensajeConfirmacion" class="mb-0">
                    ¿Está seguro de realizar esta acción?
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button" class="btn btn-warning" id="btnConfirmarAccion">
                    Confirmar
                </button>
            </div>

        </div>
    </div>
</div>