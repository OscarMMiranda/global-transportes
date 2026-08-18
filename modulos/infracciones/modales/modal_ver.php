<!-- archivo: /modulos/infracciones/modales/modal_ver.php -->

<div class="modal fade" id="modalVer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Ver Infracción</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Código:</div>
                    <div class="col-md-8" id="ver_codigo"></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Descripción:</div>
                    <div class="col-md-8" id="ver_descripcion"></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Gravedad:</div>
                    <div class="col-md-8" id="ver_gravedad"></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Puntos:</div>
                    <div class="col-md-8" id="ver_puntos"></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">% UIT:</div>
                    <div class="col-md-8" id="ver_porcentaje_uit"></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Monto Base:</div>
                    <div class="col-md-8" id="ver_monto_base"></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Entidad Emisora:</div>
                    <div class="col-md-8" id="ver_entidad_emisora"></div>
                </div>

                <hr>

                <h6 class="text-secondary">Auditoría</h6>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Creado por:</div>
                    <div class="col-md-8" id="ver_creado_por"></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Fecha creación:</div>
                    <div class="col-md-8" id="ver_fecha_creacion"></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Modificado por:</div>
                    <div class="col-md-8" id="ver_modificado_por"></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Fecha modificación:</div>
                    <div class="col-md-8" id="ver_fecha_modificacion"></div>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>
