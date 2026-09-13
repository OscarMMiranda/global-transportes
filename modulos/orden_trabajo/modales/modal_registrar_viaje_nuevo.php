<!-- archivo: /modulos/orden_trabajo/modales/modal_registrar_viaje_nuevo.php -->

<div id="modalRegistrarViaje" class="modal-corp">
    <div class="modal-corp-content">

        <!-- HEADER -->
        <div class="modal-corp-header">
            <span class="modal-title" id="tituloRegistrarViaje">Registrar Viaje</span>
            <button type="button" class="modal-close" onclick="cerrarModalViaje()">×</button>
        </div>

        <!-- BODY -->
        <div class="modal-corp-body">

            <!-- Hidden fields -->
            <input type="hidden" id="rv_orden_trabajo_id">
            <input type="hidden" id="rv_orden_vehiculo_id">

            <!-- Fecha y Semana -->
            <div class="row">
                <div class="col-md-6">
                    <label>Fecha del viaje</label>
                    <input type="date" id="rv_fecha_viaje" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>Semana</label>
                    <input type="text" id="rv_semana_viaje" class="form-control" readonly>
                </div>
            </div>

            <!-- Vehículo y Conductor -->
            <div class="row">
                <div class="col-md-6">
                    <label>Vehículo</label>
                    <select id="rv_vehiculo" class="form-control"></select>
                </div>

                <div class="col-md-6">
                    <label>Conductor</label>
                    <input type="text" id="rv_conductor" class="form-control" readonly>
                </div>
            </div>

            <!-- Origen -->
            <div class="row">
                <div class="col-md-12">
                    <label>Origen</label>
                    <select id="rv_origen" class="form-control"></select>
                </div>
            </div>

            <!-- Destino -->
            <div class="row">
                <div class="col-md-12">
                    <label>Destino</label>
                    <select id="rv_destino" class="form-control"></select>
                </div>
            </div>

            <!-- Observaciones -->
            <div class="row">
                <div class="col-md-12">
                    <label>Observaciones</label>
                    <textarea id="rv_observaciones" class="form-control"></textarea>
                </div>
            </div>

        </div>

        <!-- FOOTER -->
        <div class="modal-corp-footer">
            <button class="btn btn-success" id="btnGuardarViaje">Guardar Viaje</button>
        </div>

    </div>
</div>

<div id="modalOverlay"></div>
