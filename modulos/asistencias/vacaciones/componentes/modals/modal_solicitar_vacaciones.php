<?php
// archivo: /modulos/asistencias/vacaciones/componentes/modals/modal_solicitar_vacaciones.php
// ============================================================
// MODAL: SOLICITAR VACACIONES (VERSIÓN FINAL)
// ============================================================
?>

<div class="modal fade" id="modalSolicitarVacaciones" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header py-2">
                <h5 class="modal-title">
                    <i class="fa-solid fa-plane"></i> Solicitar vacaciones
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <div class="row g-3">

                    <!-- Empresa -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold mb-0">Empresa</label>
                        <select id="vacEmpresa" class="form-select form-select-sm">
                            <option value="">Seleccione</option>
                        </select>
                    </div>

                    <!-- Conductor -->
                    <div class="col-md-8">
                        <label class="form-label fw-bold mb-0">Conductor</label>
                        <select id="vacConductor" class="form-select form-select-sm">
                            <option value="">Seleccione</option>
                        </select>
                    </div>

                    <!-- Fecha inicio -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold mb-0">Fecha inicio</label>
                        <input type="date" id="vacFechaInicio" class="form-control form-control-sm">
                    </div>

                    <!-- Fecha fin -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold mb-0">Fecha fin</label>
                        <input type="date" id="vacFechaFin" class="form-control form-control-sm">
                    </div>

                    <!-- Días calculados -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold mb-0">Días</label>
                        <input type="number" id="vacDias" class="form-control form-control-sm" readonly>
                    </div>

                    <!-- Tipo -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold mb-0">Tipo</label>
                        <select id="vacTipo" class="form-select form-select-sm">
                            <option value="parcial">Parcial</option>
                            <option value="total">Total</option>
                        </select>
                    </div>

                    <!-- Comentario -->
                    <div class="col-md-12">
                        <label class="form-label fw-bold mb-0">Comentario</label>
                        <textarea id="vacComentario" class="form-control form-control-sm" rows="2" placeholder="Opcional"></textarea>
                    </div>

                </div>

                <!-- ALERTA -->
                <div id="vacAlert" class="alert alert-danger mt-3 d-none"></div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer py-2">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button class="btn btn-primary btn-sm" id="btnGuardarSolicitudVacaciones">
                    <i class="fa-solid fa-check"></i> Guardar solicitud
                </button>
            </div>

        </div>
    </div>
</div>
