<!-- archivo: /modulos/orden_trabajo/modales/modal_registrar_viaje.php -->

<div class="modal-corp" id="modalRegistrarViaje">

    <div class="modal-corp-content modal-lg" style="background:white; padding:20px;">

        <!-- HEADER -->
        <div class="modal-corp-header" style="background:#f7c948; color:#000; padding:10px;">
            <h4 style="margin:0;">Registrar Viaje</h4>
            <button type="button" class="btn-cerrar" onclick="cerrarModal()">×</button>
        </div>

        <!-- FORM -->
        <form id="formRegistrarViaje">

            <div class="modal-corp-body" style="padding:15px;">

                <!-- IDs ocultos -->
                <input type="hidden" id="rv_orden_trabajo_id">
                <input type="hidden" id="rv_orden_vehiculo_id">

                <!-- FILA 1 -->
                <div class="fila">
                    <div class="col">
                        <label>Fecha del viaje</label>
                        <input type="date" id="rv_fecha_viaje" class="form-control">
                    </div>

                    <div class="col">
                        <label>Semana</label>
                        <input type="number" id="rv_semana_viaje" class="form-control">
                    </div>

                    <div class="col">
                        <label>Número de Viaje (OV)</label>
                        <input type="text" id="rv_numero_viaje" class="form-control">
                    </div>
                </div>

                <!-- FILA 2 -->
                <div class="fila">
                    <div class="col">
                        <label>Origen</label>
                        <input type="text" id="rv_origen" class="form-control">
                    </div>

                    <div class="col">
                        <label>Destino</label>
                        <input type="text" id="rv_destino" class="form-control">
                    </div>
                </div>

                <!-- FILA 3 -->
                <div class="fila">
                    <div class="col">
                        <label>Tipo de Mercadería</label>
                        <select id="rv_tipo_mercaderia" class="form-select">
                            <option value="C/S">C/S - Carga Suelta</option>
                            <option value="20ST">20' ST</option>
                            <option value="40HC">40' HC</option>
                            <option value="OTRO">OTRO</option>
                        </select>
                    </div>

                    <div class="col">
                        <label>Conductor</label>
                        <input type="text" id="rv_conductor" class="form-control">
                    </div>
                </div>

                <!-- FILA 4 -->
                <div class="fila">
                    <div class="col">
                        <label>Guía Cliente</label>
                        <input type="text" id="rv_guia_cliente" class="form-control">
                    </div>

                    <div class="col">
                        <label>Guía Transporte</label>
                        <input type="text" id="rv_guia_transporte" class="form-control">
                    </div>
                </div>

                <!-- FILA 5 -->
                <div class="fila">
                    <div class="col">
                        <label>Observaciones</label>
                        <textarea id="rv_observaciones" rows="3" class="form-control"></textarea>
                    </div>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-corp-footer" style="padding:10px; text-align:right;">
                <button type="submit" class="btn btn-success">Registrar Viaje</button>
            </div>

        </form>

    </div>

</div>
