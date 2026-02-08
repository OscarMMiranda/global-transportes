<?php
// archivo: /modulos/asistencias/modales/modal_reporte_diario.php
?>

<div class="modal fade" id="modalReporteDiario" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Reporte Diario de Asistencia</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div id="alertaReporteDiario"></div>

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Fecha</label>
                        <input type="date" id="rep_fecha" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Empresa</label>
                        <select id="rep_empresa_id" class="form-select">
                            <option value="">Seleccione...</option>
                            <?php
                            $empresas = obtener_empresas($conn);
                            foreach ($empresas as $e) {
                                echo '<option value="'.$e['id'].'">'.$e['nombre'].'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Conductor</label>
                        <select id="rep_conductor_id" class="form-select">
                            <option value="">Seleccione empresa primero...</option>
                        </select>
                    </div>

                </div>

                <div id="historialDiaReporte" class="mt-4"></div>

            </div>

            <div class="modal-footer">
                <button type="button" id="btnGenerarReporteDiario" class="btn btn-info text-white">
                    Generar reporte
                </button>

                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
