<?php
	// archivo : /modulos/asistencias/vacaciones/vistas/vista_saldos.php
// ============================================================
// VISTA: SALDOS DE VACACIONES
// ============================================================
?>

<div class="card">
    <div class="card-header py-2">
        <h5 class="m-0">
            <i class="fa-solid fa-suitcase-rolling"></i> Saldos de vacaciones
        </h5>
    </div>

    <div class="card-body">

        <!-- Tabla de saldos -->
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover" id="tablaSaldosVacaciones">
                <thead class="table-light">
                    <tr>
                        <th>Conductor</th>
                        <th>Empresa</th>
                        <th>Periodo</th>
                        <th>Días ganados</th>
                        <th>Días usados</th>
                        <th>Días vendidos</th>
                        <th>Días pendientes</th>
                        <th>Estado</th>
                        <th style="width: 60px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Se llena por AJAX -->
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Loader -->
<div id="loaderSaldos" class="text-center mt-3" style="display:none;">
    <div class="spinner-border text-primary" role="status"></div>
    <div><small class="text-muted">Cargando saldos...</small></div>
</div>
