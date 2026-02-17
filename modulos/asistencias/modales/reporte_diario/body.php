<!-- archivo: modulos/asistencias/modales/reporte_diario/body.php -->

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
