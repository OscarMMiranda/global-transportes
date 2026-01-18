<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/modales/modal_editar.php
?>

<div class="modal fade" id="modalEditarTipoVehiculo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Editar Tipo de Vehículo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="editar_tv_id">

                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                    <input type="text" id="editar_tv_nombre" class="form-control" maxlength="50">
                    <div class="invalid-feedback">El nombre es obligatorio.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea id="editar_tv_descripcion" class="form-control" rows="3" maxlength="255"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Categoría <span class="text-danger">*</span></label>
                    <select id="editar_tv_categoria_id" class="form-select">
                        <option value="">Cargando categorías...</option>
                    </select>
                    <div class="invalid-feedback">Debe seleccionar una categoría.</div>
                </div>

                <hr>

                <div class="small text-muted">
                    <div><span class="fw-bold">Creado por:</span> <span id="editar_tv_creado_por"></span></div>
                    <div><span class="fw-bold">Fecha creación:</span> <span id="editar_tv_fecha_creado"></span></div>
                    <div><span class="fw-bold">Última modificación:</span> <span id="editar_tv_fecha_modificacion"></span></div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="btnActualizarTipoVehiculo" class="btn btn-primary">
                    Guardar cambios
                </button>
            </div>

        </div>
    </div>
</div>