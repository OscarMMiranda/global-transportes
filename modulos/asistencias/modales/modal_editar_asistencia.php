<?php
    // archivo: /modulos/asistencias/modales/modal_editar_asistencia.php
?>

<div class="modal fade" id="modalEditarAsistencia" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <span id="edit_titulo_contexto">Editar Asistencia</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="edit_id">

                <div class="mb-3">
                    <label class="form-label">Tipo</label>
                    <select id="edit_tipo" class="form-control"></select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hora Entrada</label>
                    <input type="time" id="edit_entrada" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Hora Salida</label>
                    <input type="time" id="edit_salida" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Observación</label>
                    <textarea id="edit_obs" class="form-control"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" id="btnGuardarEdicion">Guardar Cambios</button>
            </div>

        </div>
    </div>
</div>

