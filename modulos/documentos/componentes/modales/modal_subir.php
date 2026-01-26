<?php
// archivo: /modulos/documentos/componentes/modales/modal_subir.php
?>

<div class="modal fade" id="modalSubir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Subir Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formSubirDocumento" enctype="multipart/form-data">

                    <!-- PASO 1: ENTIDAD -->
                    <div class="mb-3">
                        <label class="form-label">Entidad</label>
                        <select id="entidad_tipo" name="entidad_tipo" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="vehiculo">Vehículo</option>
                            <option value="conductor">Conductor</option>
                            <option value="empleado">Empleado</option>
                            <option value="empresa">Empresa</option>
                        </select>
                    </div>

                    <!-- PASO 2: ENTIDAD_ID -->
                    <div class="mb-3">
                        <label id="label_entidad_id" class="form-label">Seleccione entidad</label>
                        <select id="entidad_id" name="entidad_id" class="form-select" required>
                            <option value="">Seleccione una entidad...</option>
                        </select>
                    </div>

                    <!-- PASO 3: TIPO DE DOCUMENTO -->
                    <div class="mb-3">
                        <label class="form-label">Tipo de documento</label>
                        <select id="tipo_documento_id" name="tipo_documento_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                        </select>
                    </div>

                    <!-- PASO 4: CAMPOS DINÁMICOS -->
                    <div id="contenedor_campos_tipo"></div>

                    <!-- BOTÓN GUARDAR -->
                    <button type="submit" class="btn btn-primary w-100 mt-3">Guardar</button>

                </form>
            </div>

        </div>
    </div>
</div>
