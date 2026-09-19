<!-- archivo: /modulos/orden_trabajo/modales/modal_crear.php -->

<div class="modal fade" id="modalCrearOT" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content shadow-lg">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold">Nueva Orden de Trabajo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body px-4">

                <form id="formCrearOT">

                    <!-- ============================
                         SECCIÓN: DATOS GENERALES
                    ============================ -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-secondary border-bottom pb-2">Datos Generales</h6>

                        <div class="row mt-3">

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Número OT</label>
                                <input type="text" name="numero_ot" id="crear_numero_ot"
                                       class="form-control" placeholder="0001-2026">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Fecha</label>
                                <input type="date" name="fecha" id="crear_fecha" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Semana</label>
                                <!-- SOLO VISUAL: SXX-YYYY -->
                                <input type="text" id="crear_semana_ot"
                                       class="form-control" readonly>
                                <!-- REAL PARA BD: INT -->
                                <input type="hidden" name="semana_ot" id="crear_semana_ot_real">
                            </div>

                        </div>
                    </div>

                    <!-- ============================
                         SECCIÓN: CLIENTE Y EMPRESA
                    ============================ -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-secondary border-bottom pb-2">Cliente y Empresa</h6>

                        <div class="row mt-3">

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Cliente</label>
                                <select name="cliente_id" id="crear_cliente_id" class="form-select"></select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Orden de Cliente (OC)</label>
                                <input type="text" name="oc_cliente" id="crear_oc_cliente"
                                       class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Empresa</label>
                                <select name="empresa_id" id="crear_empresa_id" class="form-select"></select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tipo de Orden</label>
                                <select name="tipo_ot_id" id="crear_tipo_ot" class="form-select"></select>
                            </div>

                        </div>
                    </div>

                    <!-- ============================
                         SECCIÓN: TIPO DE OPERACIÓN
                    ============================ -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-secondary border-bottom pb-2">Tipo de Operación</h6>

                        <div class="row mt-3">

                            <!-- IMPORTACIÓN -->
                            <div class="col-md-6 mb-3" id="crear_campo_importacion" style="display:none;">
                                <label class="form-label fw-semibold">Número DAM / DUA</label>
                                <input type="text" name="numero_dam" id="crear_numero_dam"
                                       class="form-control">
                            </div>

                            <!-- EXPORTACIÓN -->
                            <div class="col-md-6 mb-3" id="crear_campo_exportacion" style="display:none;">
                                <label class="form-label fw-semibold">Número Booking</label>
                                <input type="text" name="numero_booking" id="crear_numero_booking"
                                       class="form-control">
                            </div>

                            <!-- NACIONAL -->
                            <div class="col-md-6 mb-3" id="crear_campo_nacional" style="display:none;">
                                <label class="form-label fw-semibold">Otros</label>
                                <input type="text" name="otros" id="crear_otros" class="form-control">
                            </div>

                        </div>
                    </div>

                </form>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer py-3">
                <button type="submit" form="formCrearOT" class="btn btn-primary px-4 fw-semibold">
                    Guardar Orden
                </button>
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    Cancelar
                </button>
            </div>

        </div>
    </div>
</div>
