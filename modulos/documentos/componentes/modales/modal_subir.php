<?php
// archivo: /modulos/documentos/componentes/modales/modal_subir.php
?>
<div class="modal fade" id="modalSubir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Subir Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="formSubirDocumento" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label">Tipo de documento</label>
                        <select name="tipo_documento_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Entidad</label>
                        <select name="entidad_tipo" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="vehiculo">Vehículo</option>
                            <option value="conductor">Conductor</option>
                            <option value="empresa">Empresa</option>
                            <option value="empleado">Empleado</option>
                            <option value="cliente">Cliente</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Número</label>
                        <input type="text" name="numero" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha de inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha de vencimiento</label>
                        <input type="date" name="fecha_vencimiento" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Archivo PDF</label>
                        <input type="file" name="archivo" accept="application/pdf" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Guardar</button>

                </form>

            </div>

        </div>
    </div>
</div>