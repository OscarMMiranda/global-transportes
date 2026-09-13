<!--  archivo: /modulos/orden_trabajo/modales/modal_editar.php -->

<div class="modal fade" id="modalEditarOT" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Editar Orden de Trabajo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="formEditarOT">

                    <div class="row">

                        <!-- Número OT (NO editable) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Número OT</label>
                            <input type="text" name="numero_ot" id="editar_numero_ot" class="form-control" readonly>
                        </div>

                        <!-- Fecha -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha</label>
                            <input type="date" name="fecha" id="editar_fecha" class="form-control">
                        </div>

                        <!-- Semana (NO editable) -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Semana</label>
                            <input type="text" name="semana_ot" id="editar_semana_ot" class="form-control" readonly>
                        </div>

                        <!-- Cliente -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cliente</label>
                            <select name="cliente_id" id="editar_cliente_id" class="form-select"></select>
                        </div>

                        <!-- OC Cliente -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Orden de Cliente (OC)</label>
                            <input type="text" name="oc_cliente" id="editar_oc_cliente" class="form-control">
                        </div>

                        <!-- Empresa -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Empresa</label>
                            <select name="empresa_id" id="editar_empresa_id" class="form-select"></select>
                        </div>

                        <!-- Tipo OT -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Orden</label>
                            <select name="tipo_ot_id" id="editar_tipo_ot" class="form-select"></select>
                        </div>

                        <!-- ============================
                             CAMPOS DINÁMICOS
                           ============================ -->

                        <!-- IMPORTACIÓN -->
                        <div class="col-md-6 mb-3" id="campo_importacion" style="display:none;">
                            <label class="form-label">Número DAM / DUA</label>
                            <input type="text" name="numero_dam" id="editar_numero_dam" class="form-control">
                        </div>

                        <!-- EXPORTACIÓN -->
                        <div class="col-md-6 mb-3" id="campo_exportacion" style="display:none;">
                            <label class="form-label">Número Booking</label>
                            <input type="text" name="numero_booking" id="editar_numero_booking" class="form-control">
                        </div>

                        <!-- NACIONAL -->
                        <div class="col-md-6 mb-3" id="campo_nacional" style="display:none;">
                            <label class="form-label">Otros</label>
                            <input type="text" name="otros" id="editar_otros" class="form-control">
                        </div>

                    </div>

                    <input type="hidden" name="id" id="editar_id">

                </form>

            </div>

            <div class="modal-footer">
                <button type="submit" form="formEditarOT" class="btn btn-warning">Guardar cambios</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
