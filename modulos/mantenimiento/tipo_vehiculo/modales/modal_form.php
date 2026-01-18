<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/modales/modal_form.php
?>

<div class="modal fade" id="modalTipoVehiculo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tituloModalTipoVehiculo">
                    Nuevo Tipo de Vehículo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="formTipoVehiculo">

                <div class="modal-body">

                    <input type="hidden" name="id" id="tv_id">

                    <div class="mb-3">
                        <label class="form-label">Nombre *</label>
                        <input type="text" class="form-control" name="nombre" id="tv_nombre" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="tv_descripcion" rows="3"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-primary" id="btnGuardarTipoVehiculo">
                        Guardar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>