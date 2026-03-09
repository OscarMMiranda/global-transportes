<?php
	//	archivo: /modulos/asistencias/vacaciones/componentes/modals/modal_movimientos.php	
// ============================================================
// MODAL: MOVIMIENTOS DE VACACIONES (AUDITORÍA COMPLETA)
// ============================================================
?>

<div class="modal fade" id="modalMovimientos" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header py-2">
                <h5 class="modal-title">
                    <i class="fa-solid fa-list"></i> Movimientos de vacaciones
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- Filtros internos -->
                <div class="row g-2 mb-3">

                    <div class="col-md-4">
                        <label class="form-label mb-0">Conductor</label>
                        <select id="movConductor" class="form-select form-select-sm">
                            <option value="">Todos</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label mb-0">Tipo de movimiento</label>
                        <select id="movTipo" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            <option value="ganado">Ganado</option>
                            <option value="usado">Usado</option>
                            <option value="vendido">Vendido</option>
                            <option value="ajuste">Ajuste</option>
                            <option value="solicitud">Solicitud</option>
                            <option value="aprobacion">Aprobación</option>
                            <option value="rechazo">Rechazo</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label mb-0">Año</label>
                        <select id="movAnio" class="form-select form-select-sm">
                            <option value="">Todos</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button id="btnFiltrarMovimientos" class="btn btn-primary btn-sm w-100 mt-4">
                            <i class="fa-solid fa-filter"></i> Filtrar
                        </button>
                    </div>

                </div>

                <!-- Tabla de movimientos -->
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="tablaMovimientosVacaciones">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Días</th>
                                <th>Periodo</th>
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
                <div id="loaderMovimientos" class="text-center mt-3 d-none">
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
