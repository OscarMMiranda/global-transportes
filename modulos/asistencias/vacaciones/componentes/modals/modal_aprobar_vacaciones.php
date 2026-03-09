<?php
	//	archivo : /modulos/asistencias/vacaciones/componentes/modals/modal_aprobar_vacaciones.php
// ============================================================
// MODAL: APROBAR / RECHAZAR SOLICITUD DE VACACIONES
// ============================================================
?>

<div class="modal fade" id="modalAprobarVacaciones" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header py-2">
                <h5 class="modal-title">
                    <i class="fa-solid fa-check-circle"></i> Aprobar solicitud de vacaciones
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <input type="hidden" id="aprobVacSolicitudID">

                <div class="row g-3">

                    <!-- Empresa -->
                    <div class="col-md-4">
                        <label class="form-label">Empresa</label>
                        <input type="text" id="aprobVacEmpresa" class="form-control form-control-sm" readonly>
                    </div>

                    <!-- Conductor -->
                    <div class="col-md-8">
                        <label class="form-label">Conductor</label>
                        <input type="text" id="aprobVacConductor" class="form-control form-control-sm" readonly>
                    </div>

                    <!-- Fecha inicio -->
                    <div class="col-md-4">
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" id="aprobVacFechaInicio" class="form-control form-control-sm" readonly>
                    </div>

                    <!-- Fecha fin -->
                    <div class="col-md-4">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" id="aprobVacFechaFin" class="form-control form-control-sm" readonly>
                    </div>

                    <!-- Días solicitados -->
                    <div class="col-md-4">
                        <label class="form-label">Días solicitados</label>
                        <input type="number" id="aprobVacDias" class="form-control form-control-sm" readonly>
                    </div>

                    <!-- Saldo disponible -->
                    <div class="col-md-4">
                        <label class="form-label">Saldo disponible</label>
                        <input type="number" id="aprobVacSaldo" class="form-control form-control-sm" readonly>
                    </div>

                    <!-- Comentario del solicitante -->
                    <div class="col-md-12">
                        <label class="form-label">Comentario del solicitante</label>
                        <textarea id="aprobVacComentario" class="form-control form-control-sm" rows="2" readonly></textarea>
                    </div>

                    <!-- Comentario de RRHH -->
                    <div class="col-md-12">
                        <label class="form-label">Comentario de RRHH</label>
                        <textarea id="aprobVacComentarioRRHH" class="form-control form-control-sm" rows="2"></textarea>
                    </div>

                </div>

                <!-- ALERTA -->
                <div id="aprobVacAlert" class="alert alert-danger mt-3 d-none"></div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer py-2">

                <button class="btn btn-danger btn-sm" id="btnRechazarVacaciones">
                    <i class="fa-solid fa-xmark"></i> Rechazar
                </button>

                <button class="btn btn-success btn-sm" id="btnAprobarVacaciones">
                    <i class="fa-solid fa-check"></i> Aprobar
                </button>

            </div>

        </div>
    </div>
</div>
