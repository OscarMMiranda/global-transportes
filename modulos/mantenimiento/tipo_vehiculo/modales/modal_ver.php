<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/modales/modal_ver.php
?>

<div class="modal fade" id="modalVerTipoVehiculo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">Detalle de Tipo de Vehículo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row mb-2">
                    <div class="col-md-4">
                        <span class="fw-bold">ID:</span>
                        <div id="ver_tv_id"></div>
                    </div>
                    <div class="col-md-4">
                        <span class="fw-bold">Nombre:</span>
                        <div id="ver_tv_nombre"></div>
                    </div>
                    <div class="col-md-4">
                        <span class="fw-bold">Estado:</span>
                        <div id="ver_tv_estado"></div>
                    </div>
                </div>

                <div class="mb-2">
                    <span class="fw-bold">Categoría:</span>
                    <div id="ver_tv_categoria"></div>
                </div>

                <div class="mb-2">
                    <span class="fw-bold">Descripción:</span>
                    <div id="ver_tv_descripcion"></div>
                </div>

                <hr>

                <div class="small text-muted">
                    <div><span class="fw-bold">Creado por:</span> <span id="ver_tv_creado_por"></span></div>
                    <div><span class="fw-bold">Fecha creación:</span> <span id="ver_tv_fecha_creado"></span></div>
                    <div><span class="fw-bold">Última modificación:</span> <span id="ver_tv_fecha_modificacion"></span></div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>