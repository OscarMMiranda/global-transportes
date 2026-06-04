<?php
// archivo: /modulos/orden_trabajo/modales/modal_eliminar.php
?>

<div class="modal fade" id="modalEliminarOT" tabindex="-1" aria-labelledby="modalEliminarOTLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalEliminarOTLabel">
                    <i class="fa-solid fa-trash"></i> Eliminar Orden de Trabajo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <p class="mb-3">
                    ¿Estás seguro de que deseas <strong>eliminar</strong> esta Orden de Trabajo?
                </p>

                <div class="alert alert-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Esta acción es permanente y no se puede deshacer.
                </div>

                <!-- Campo oculto para ID -->
                <input type="hidden" id="eliminar_id_ot">

                <div id="loaderEliminarOT" class="text-center my-3" style="display:none;">
                    <div class="spinner-border text-danger"></div>
                    <p class="mt-2">Procesando eliminación...</p>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button" class="btn btn-danger btn-sm" id="btnConfirmarEliminarOT" onclick="confirmarEliminarOT()">
                    <i class="fa-solid fa-trash"></i> Eliminar
                </button>
            </div>

        </div>
    </div>
</div>
