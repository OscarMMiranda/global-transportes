<div class="modal fade" id="modalEditarDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fa fa-edit me-2"></i> Editar Documento
                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="formEditarDocumento" enctype="multipart/form-data">

                    <input type="hidden" name="id" id="edit_doc_id">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Documento</label>
                            <select name="tipo_documento_id" id="edit_tipo_documento" class="form-select" required>
                                <option value="">Cargando...</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Número</label>
                            <input type="text" name="numero" id="edit_numero" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" id="edit_fecha_inicio" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Fecha Vencimiento</label>
                            <input type="date" name="fecha_vencimiento" id="edit_fecha_vencimiento" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reemplazar Archivo (opcional)</label>
                        <input type="file" name="archivo" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="edit_observaciones" class="form-control" rows="2"></textarea>
                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-warning" onclick="guardarEdicionDocumento()">
                    <i class="fa fa-save me-1"></i> Guardar Cambios
                </button>
            </div>

        </div>
    </div>
</div>
