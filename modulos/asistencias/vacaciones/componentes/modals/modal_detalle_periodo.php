<?php
	//	archivo: /modulos/asistencias/vacaciones/componentes/modals/modal_detalle_periodo.php
// ============================================================
// MODAL: DETALLE DE PERIODO VACACIONAL
// ============================================================
?>

<div class="modal fade" id="modalDetallePeriodo" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header py-2">
                <h5 class="modal-title">
                    <i class="fa-solid fa-calendar-check"></i> Detalle del periodo vacacional
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <input type="hidden" id="detallePeriodoID">

                <!-- Datos del periodo -->
                <div class="row g-3 mb-3">

                    <div class="col-md-4">
                        <label class="form-label">Periodo inicio</label>
                        <input type="date" id="detallePeriodoInicio" class="form-control form-control-sm" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Periodo fin</label>
                        <input type="date" id="detallePeriodoFin" class="form-control form-control-sm" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <input type="text" id="detallePeriodoEstado" class="form-control form-control-sm" readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Días ganados</label>
                        <input type="number" id="detalleDiasGanados" class="form-control form-control-sm" readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Días usados</label>
                        <input type="number" id="detalleDiasUsados" class="form-control form-control-sm" readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Días vendidos</label>
                        <input type="number" id="detalleDiasVendidos" class="form-control form-control-sm" readonly>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Días pendientes</label>
                        <input type="number" id="detalleDiasPendientes" class="form-control form-control-sm" readonly>
                    </div>

                </div>

                <hr>

                <!-- Movimientos del periodo -->
                <h6 class="mb-2">
                    <i class="fa-solid fa-list"></i> Movimientos del periodo
                </h6>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="tablaMovimientosPeriodo">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Días</th>
                                <th>Descripción</th>
                                <th>Usuario</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Se llena por AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Loader -->
                <div id="loaderDetallePeriodo" class="text-center mt-3 d-none">
                    <div class="spinner-border text-primary"></div>
                    <div><small class="text-muted">Cargando movimientos...</small></div>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer py-2">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
