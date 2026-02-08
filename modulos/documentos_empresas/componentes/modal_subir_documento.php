<!-- /modulos/documentos_empresas/componentes/modal_subir_documento.php -->

<div class="modal fade" id="modalSubirDocumento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tituloModalSubir">Subir documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formSubirDocumento" enctype="multipart/form-data">

                <div class="modal-body">

                    <input type="hidden" id="empresa_id" name="empresa_id">
                    <input type="hidden" id="tipo_documento_id" name="tipo_documento_id">

                    <div class="mb-3">
                        <label class="form-label">Número</label>
                        <input type="text" name="numero" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha de inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha de vencimiento</label>
                        <input type="date" name="fecha_vencimiento" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Archivo</label>
                        <input type="file" name="archivo" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" id="btnGuardarDocumento" class="btn btn-success">
                        Guardar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
