<?php
// archivo: /modulos/papeletas/modales/modal_ver_archivos.php
?>

<div class="modal fade" id="modalVerArchivos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Archivos de la papeleta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- FORMULARIO DE SUBIDA -->
                <form id="formSubirArchivo" enctype="multipart/form-data">

                    <!-- 🔥 CORREGIDO: ESTE HIDDEN DEBE ESTAR DENTRO DEL FORM -->
                    <input type="hidden" name="id" id="archivos_papeleta_id">

                    <div class="row mb-3">

                        <div class="col-md-8">
                            <label class="form-label">Seleccionar archivo</label>
                            <input type="file" name="archivo" id="archivo" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Descripción del archivo</label>
                            <input type="text" name="descripcion_archivo" id="descripcion_archivo" class="form-control" placeholder="Opcional">
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100" id="btnSubirArchivo">
                                <i class="fa fa-upload"></i> Subir archivo
                            </button>
                        </div>
                    </div>

                </form>

                <hr>

                <!-- LISTADO DE ARCHIVOS -->
                <div id="contenedorArchivosPapeleta">
                    <div class="text-center text-muted py-4">
                        Cargando archivos...
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>
