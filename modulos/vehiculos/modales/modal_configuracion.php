<?php
// archivo: /modulos/vehiculos/modales/modal_configuracion.php
?>

<div class="modal fade" id="modalConfig" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Nueva configuración de vehículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formConfig">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la configuración</label>
                        <input type="text" name="nombre" class="form-control" required maxlength="100">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" maxlength="255"></textarea>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>