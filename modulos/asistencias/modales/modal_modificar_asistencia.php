<?php
// archivo: /modulos/asistencias/modales/modal_modificar_asistencia.php
?>

<!-- Modal: Modificar Asistencia -->
<div class="modal fade" id="modalModificarAsistencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fa-solid fa-pen-to-square"></i> Modificar Asistencia
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- ALERTA VISUAL -->
                <div id="alertaModificarAsistencia"></div>

                <!-- FORMULARIO -->
                <form id="formModificarAsistencia">

                    <input type="hidden" id="asistencia_id">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Empresa</label>
                            <select id="empresa_id_edit" class="form-select">
                                <option value="">Seleccione...</option>
                                <!-- Se llena vía AJAX -->
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Conductor</label>
                            <select id="conductor_id_edit" class="form-select">
                                <option value="">Seleccione...</option>
                                <!-- Se llena vía AJAX -->
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tipo</label>
                            <select id="codigo_tipo_edit" class="form-select">
                                <option value="">Seleccione...</option>
                                <!-- Se llena vía AJAX -->
                            </select>
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

            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="button" id="btnGuardarCambiosAsistencia" class="btn btn-warning">
                    Guardar cambios
                </button>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
