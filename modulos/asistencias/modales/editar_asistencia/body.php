<!-- archivo: modulos/asistencias/modales/editar_asistencia/body.php -->

<div class="modal-body">

    <input type="hidden" id="edit_id">

    <div class="form-group mb-2">
        <label for="edit_tipo" class="form-label">Tipo</label>
        <select id="edit_tipo" class="form-control"></select>
    </div>

    <div class="form-group mb-2">
        <label for="edit_entrada" class="form-label">Hora Entrada</label>
        <input type="time" id="edit_entrada" class="form-control">
    </div>

    <div class="form-group mb-1">
        <label for="edit_salida" class="form-label">Hora Salida</label>
        <input type="time" id="edit_salida" class="form-control">
    </div>

    <div class="form-group mb-1">
        <label for="edit_obs" class="form-label">Observación</label>
        <textarea id="edit_obs" class="form-control" rows="2"></textarea>
    </div>

</div>
