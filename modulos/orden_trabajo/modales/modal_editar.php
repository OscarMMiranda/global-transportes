<?php
// archivo: /modulos/orden_trabajo/modales/modal_editar.php
?>

<div class="modal fade" id="modalEditarOT" tabindex="-1" aria-labelledby="modalEditarOTLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalEditarOTLabel">
                    <i class="fa-solid fa-pen-to-square"></i> Editar Orden de Trabajo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <div id="loaderEditarOT" class="text-center my-3" style="display:none;">
                    <div class="spinner-border text-info"></div>
                    <p class="mt-2">Cargando información...</p>
                </div>

                <form id="formEditarOT" style="display:none;">

                    <input type="hidden" id="editar_id_ot" name="id">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Número OT:</label>
                            <input type="text" id="editar_numero_ot" name="numero_ot" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Fecha:</label>
                            <input type="date" id="editar_fecha" name="fecha" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Cliente:</label>
                            <input type="text" id="editar_cliente" name="cliente" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Empresa:</label>
                            <input type="text" id="editar_empresa" name="empresa" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Tipo OT:</label>
                            <input type="text" id="editar_tipo_ot" name="tipo_ot" class="form-control form-control-sm">
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">OC Cliente:</label>
                            <input type="text" id="editar_oc_cliente" name="oc_cliente" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Descripción:</label>
                        <textarea id="editar_descripcion" name="descripcion" class="form-control form-control-sm" rows="3"></textarea>
                    </div>

                </form>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button" class="btn btn-info btn-sm" onclick="guardarEdicionOT()">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                </button>
            </div>

        </div>
    </div>
</div>
