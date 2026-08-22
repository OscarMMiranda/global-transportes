<?php
// archivo: /modulos/orden_trabajo/modales/modal_anular.php
?>

<div class="modal fade" id="modalAnularOT" tabindex="-1" aria-labelledby="modalAnularOTLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalAnularOTLabel">
                    <i class="fa-solid fa-ban"></i> Anular Orden de Trabajo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <p class="mb-3">
                    ¿Estás seguro de que deseas <strong>anular</strong> esta Orden de Trabajo?
                </p>

                <div class="alert alert-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    La OT no será eliminada, pero quedará marcada como <strong>Anulada</strong>.
                </div>

                <!-- Campo oculto para ID -->
                <input type="hidden" id="anular_id_ot">

                <div id="loaderAnularOT" class="text-center my-3" style="display:none;">
                    <div class="spinner-border text-warning"></div>
                    <p class="mt-2">Procesando anulación...</p>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button" class="btn btn-warning btn-sm" id="btnConfirmarAnularOT" onclick="confirmarAnularOT()">
                    <i class="fa-solid fa-ban"></i> Anular
                </button>
            </div>

        </div>
    </div>
</div>
