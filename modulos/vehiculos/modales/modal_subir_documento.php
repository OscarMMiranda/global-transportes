<?php
// archivo: /modulos/vehiculos/modales/modal_subir_documento.php
?>
<div class="modal fade" id="modalSubirDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fa fa-upload me-2"></i> Subir Documento
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="formSubirDocumento" enctype="multipart/form-data">

                    <input type="hidden" name="entidad_tipo" value="vehiculo">
                    <input type="hidden" name="entidad_id" id="doc_entidad_id">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Documento</label>

                            <!-- SELECT CORRECTO (solo uno) -->
                            <select name="tipo_documento_id" id="selectTipoDocumento" class="form-select" required>
                                <option value="">Cargando...</option>
                            </select>

                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Número</label>
                            <input type="text" name="numero" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Fecha Vencimiento</label>
                            <input type="date" name="fecha_vencimiento" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Archivo (PDF / JPG / PNG)</label>
                        <input type="file" name="archivo" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2"></textarea>
                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" onclick="guardarDocumento()">
                    <i class="fa fa-save me-1"></i> Guardar
                </button>
            </div>

        </div>
    </div>
</div>
