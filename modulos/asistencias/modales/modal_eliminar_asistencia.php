<?php
// archivo: /modulos/asistencias/modales/modal_eliminar_asistencia.php
?>

<div class="modal fade" id="modalEliminarAsistencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fa-solid fa-trash"></i> Eliminar Asistencia
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">
                <p class="mb-3">
                    ¿Está seguro que desea eliminar esta asistencia?
                </p>

                <input type="hidden" id="asistencia_id_eliminar">
            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button" id="btnConfirmarEliminarAsistencia" class="btn btn-danger">
                    Eliminar
                </button>
            </div>

        </div>
    </div>
</div>
