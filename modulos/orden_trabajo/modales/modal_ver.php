<?php
// archivo: /modulos/orden_trabajo/modales/modal_ver.php
?>

<div class="modal fade" id="modalVerOT" tabindex="-1" aria-labelledby="modalVerOTLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalVerOTLabel">
                    <i class="fa-solid fa-eye"></i> Ver Orden de Trabajo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <div id="loaderVerOT" class="text-center my-3" style="display:none;">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Cargando información...</p>
                </div>

                <div id="contenidoVerOT" style="display:none;">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Número OT:</label>
                            <p id="ver_numero_ot" class="form-control-plaintext"></p>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Fecha:</label>
                            <p id="ver_fecha" class="form-control-plaintext"></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Cliente:</label>
                            <p id="ver_cliente" class="form-control-plaintext"></p>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Empresa:</label>
                            <p id="ver_empresa" class="form-control-plaintext"></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Tipo OT:</label>
                            <p id="ver_tipo_ot" class="form-control-plaintext"></p>
                        </div>

                        <div class="col-md-6">
                            <label class="fw-bold">Estado:</label>
                            <p id="ver_estado" class="form-control-plaintext"></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">OC Cliente:</label>
                        <p id="ver_oc_cliente" class="form-control-plaintext"></p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Descripción:</label>
                        <p id="ver_descripcion" class="form-control-plaintext"></p>
                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
