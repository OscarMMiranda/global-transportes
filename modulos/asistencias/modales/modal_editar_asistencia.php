<?php
// archivo: /modulos/asistencias/modales/modal_editar_asistencia.php
?>

<div class="modal fade" id="modalEditarAsistencia" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <span id="edit_titulo_contexto">Editar Asistencia</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <input type="hidden" id="edit_id">

                <div class="form-group mb-3">
                    <label for="edit_tipo" class="form-label">Tipo</label>
                    <select id="edit_tipo" class="form-control"></select>
                </div>

                <div class="form-group mb-3">
                    <label for="edit_entrada" class="form-label">Hora Entrada</label>
                    <input type="time" id="edit_entrada" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="edit_salida" class="form-label">Hora Salida</label>
                    <input type="time" id="edit_salida" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label for="edit_obs" class="form-label">Observación</label>
                    <textarea id="edit_obs" class="form-control" rows="2"></textarea>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Cancelar
                </button>

                <button type="button" class="btn btn-primary" id="btnGuardarEdicion">
                    Guardar Cambios
                </button>
            </div>

        </div>
    </div>
</div>

