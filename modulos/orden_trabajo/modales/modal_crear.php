<!-- archivo: /modulos/orden_trabajo/modales/modal_crear.php -->

<div class="modal fade" id="modalCrearOT" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Crear Nueva Orden de Trabajo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="formCrearOT">

                    <div class="row">

                        <!-- Número OT -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Número OT</label>
                            <input type="text" name="numero_ot" id="crear_numero_ot" class="form-control" placeholder="0001-2026">
                        </div>

                        <!-- Fecha -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" name="fecha" id="crear_fecha" class="form-control">
                        </div>

                        <!-- Semana -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Semana</label>
                            <input type="text" name="semana_ot" id="crear_semana_ot" class="form-control" readonly>
                        </div>

                        <!-- Cliente -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cliente</label>
                            <select name="cliente_id" id="crear_cliente_id" class="form-select"></select>
                        </div>

                        <!-- OC Cliente -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Orden de Cliente (OC)</label>
                            <input type="text" name="oc_cliente" id="crear_oc_cliente" class="form-control">
                        </div>

                        <!-- Empresa -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Empresa</label>
                            <select name="empresa_id" id="crear_empresa_id" class="form-select"></select>
                        </div>

                        <!-- Tipo OT -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Orden</label>
                            <select name="tipo_ot_id" id="crear_tipo_ot" class="form-select"></select>
                        </div>

                        <!-- Estado -->
                        <!-- <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <select name="estado_id" id="crear_estado_id" class="form-select"></select>
                        </div> -->

                        <!-- IMPORTACIÓN -->
                        <div class="col-md-6 mb-3" id="crear_campo_importacion" style="display:none;">
                            <label class="form-label">Número DAM / DUA</label>
                            <input type="text" name="numero_dam" id="crear_numero_dam" class="form-control">
                        </div>

                        <!-- EXPORTACIÓN -->
                        <div class="col-md-6 mb-3" id="crear_campo_exportacion" style="display:none;">
                            <label class="form-label">Número Booking</label>
                            <input type="text" name="numero_booking" id="crear_numero_booking" class="form-control">
                        </div>

                        <!-- NACIONAL -->
                        <div class="col-md-6 mb-3" id="crear_campo_nacional" style="display:none;">
                            <label class="form-label">Otros</label>
                            <input type="text" name="otros" id="crear_otros" class="form-control">
                        </div>

                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button type="submit" form="formCrearOT" class="btn btn-primary">Guardar Orden</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
