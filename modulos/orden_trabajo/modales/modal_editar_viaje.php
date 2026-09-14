<?php
// ======================================================
//  MODAL: modal_editar_viaje.php
//  RESPONSABILIDAD: Editar datos del viaje existente
// ======================================================
?>

<div id="modalEditarViaje" class="modal-corp">

    <div class="modal-corp-content">

        <!-- HEADER -->
        <div class="modal-corp-header">
            <h5 class="modal-corp-title">Editar viaje</h5>
            <button class="modal-corp-close" onclick="cerrarModalEditarViaje()">×</button>
        </div>

        <!-- BODY -->
        <div class="modal-corp-body">

            <!-- ID DEL VIAJE -->
            <input type="hidden" id="ev_viaje_id">

            <!-- FECHA DE VIAJE -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Fecha del viaje</label>
                    <input type="date" id="ev_fecha_viaje" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Semana</label>
                    <input type="text" id="ev_semana_viaje" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Número de viaje</label>
                    <input type="text" id="ev_numero_viaje" class="form-control" readonly>
                </div>
            </div>

            <!-- VEHÍCULO Y CONDUCTOR -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Vehículo</label>
                    <select id="ev_vehiculo_id" class="form-select"></select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Conductor</label>
                    <select id="ev_conductor_id" class="form-select"></select>
                </div>
            </div>

            <!-- ORIGEN / DESTINO -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Origen</label>
                    <input type="text" id="ev_origen" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Destino</label>
                    <input type="text" id="ev_destino" class="form-control">
                </div>
            </div>

            <!-- ZONA / DISTANCIA -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Zona</label>
                    <input type="text" id="ev_zona" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Distancia (km)</label>
                    <input type="number" id="ev_distancia" class="form-control">
                </div>
            </div>

            <!-- OBSERVACIONES -->
            <div class="mb-3">
                <label class="form-label">Observaciones</label>
                <textarea id="ev_observaciones" class="form-control" rows="3"></textarea>
            </div>

        </div>

        <!-- FOOTER -->
        <div class="modal-corp-footer">
            <button class="btn btn-secondary" onclick="cerrarModalEditarViaje()">Cerrar</button>
            <button class="btn btn-primary" id="btnGuardarEditarViaje">Guardar cambios</button>
        </div>

    </div>

</div>

<!-- OVERLAY -->
<div id="modalEditarViajeOverlay" class="modal-corp-overlay"></div>
