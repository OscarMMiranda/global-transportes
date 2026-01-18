<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/modales/modal_crear.php
?>

<div class="modal fade" id="modalCrearTipoVehiculo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Nuevo Tipo de Vehículo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                    <input type="text" id="crear_tv_nombre" class="form-control" maxlength="50">
                    <div class="invalid-feedback">El nombre es obligatorio.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea id="crear_tv_descripcion" class="form-control" rows="3" maxlength="255"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Categoría <span class="text-danger">*</span></label>
                    <select id="crear_tv_categoria_id" class="form-select">
                        <option value="">Cargando categorías...</option>
                    </select>
                    <div class="invalid-feedback">Debe seleccionar una categoría.</div>
                </div>

                <div class="alert alert-warning d-none" id="alertSinCategorias">
                    No existen categorías de vehículo. Registre una categoría antes de crear tipos de vehículo.
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnGuardarTipoVehiculo" class="btn btn-primary">
                    Guardar
                </button>
            </div>

        </div>
    </div>
</div>