<?php
// archivo: /modulos/asistencias/modales/modificar/body.php
?>

<div class="modal-body">

    <div id="alertaModificarAsistencia"></div>

    <form id="formModificarAsistencia">

        <input type="hidden" id="asistencia_id">
        <input type="hidden" id="empresa_id_hidden">
        <input type="hidden" id="conductor_id_hidden">

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Empresa</label>
                <input type="text" id="empresa_id_edit" class="form-control" disabled>
            </div>

            <div class="col-md-6">
                <label class="form-label">Conductor</label>
                <input type="text" id="conductor_id_edit" class="form-control" disabled>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tipo</label>
                <select id="codigo_tipo_edit" class="form-select"></select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha</label>
                <input type="date" id="fecha_edit" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Feriado</label>
                <input type="text" id="es_feriado_edit" class="form-control" disabled>
            </div>

            <div class="col-md-6">
                <label class="form-label">Hora Entrada</label>
                <input type="time" id="hora_entrada_edit" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Hora Salida</label>
                <input type="time" id="hora_salida_edit" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label">Observación</label>
                <textarea id="observacion_edit" class="form-control" rows="2"></textarea>
            </div>

        </div>

    </form>

</div>
