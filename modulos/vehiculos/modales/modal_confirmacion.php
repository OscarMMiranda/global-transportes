<?php
// archivo: /includes/modales/modal_confirmacion.php
?>

<div class="modal fade" id="modalConfirmacion" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content shadow">

            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title" id="confirmTitulo">Confirmar acción</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="confirmMensaje" style="font-size: 0.95rem;">
                ¿Está seguro de continuar?
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button" class="btn btn-primary btn-sm" id="btnConfirmarAccion">
                    Aceptar
                </button>
            </div>

        </div>
    </div>
</div>