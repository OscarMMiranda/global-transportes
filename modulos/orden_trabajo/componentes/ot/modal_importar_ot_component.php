<?php
// ======================================================
//  COMPONENTE: modal_importar_ot_component.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Importación corporativa de OT
//  GLOBAL 2026 — Arquitectura Limpia (Optimizado Visual)
// ======================================================
?>

<div class="modal fade"
     id="modalImportarOT"
     tabindex="-1"
     aria-hidden="true"
     data-componente="modal_importar_ot"
     data-version="4.1">

    <div class="modal-dialog modal-md modal-dialog-scrollable">
        <div class="modal-content card-corp">

            <!-- HEADER corporativo -->
            <div class="modal-header bg-corp-info text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-file-import fa-fw me-1"></i>
                    Importar Órdenes de Trabajo
                </h5>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- Overlay interno corporativo -->
                <div id="overlay-importar-ot" class="overlay-corp d-none">
                    <div class="overlay-corp-content">
                        <div class="spinner-border text-light"></div>
                        <span>Procesando archivo...</span>
                    </div>
                </div>

                <form id="formImportarOT" enctype="multipart/form-data">

                    <!-- Archivo -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Archivo Excel o CSV:</label>
                        <input type="file"
                               name="archivo_ot"
                               id="archivo_ot"
                               class="form-control form-control-sm"
                               accept=".xlsx,.xls,.csv"
                               required>
                        <small class="text-muted">
                            Formatos permitidos: XLSX, XLS, CSV
                        </small>
                    </div>

                    <!-- Tipo de importación -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Modo de importación:</label>
                        <select name="modo_importacion"
                                id="modo_importacion"
                                class="form-select form-select-sm">
                            <option value="AGREGAR">Agregar nuevas OT</option>
                            <option value="ACTUALIZAR">Actualizar OT existentes</option>
                        </select>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Observaciones:</label>
                        <textarea name="observaciones"
                                  id="observaciones"
                                  class="form-control form-control-sm"
                                  rows="3"
                                  placeholder="Opcional"></textarea>
                    </div>

                </form>

                <!-- Resultado -->
                <div id="resultadoImportacionOT" class="mt-3 d-none">
                    <div class="alert alert-info">
                        <strong>Resultado de la importación:</strong>
                        <div id="resultadoImportacionOTDetalle"></div>
                    </div>
                </div>

            </div>

            <!-- FOOTER corporativo -->
            <div class="modal-footer bg-light">

                <button type="button"
                        class="btn btn-corp-secondary btn-sm px-3"
                        data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark fa-fw me-1"></i>
                    Cancelar
                </button>

                <button type="button"
                        class="btn btn-corp-info btn-sm px-3 fw-bold"
                        id="btnProcesarImportacionOT"
                        onclick="procesarImportacionOT()">
                    <i class="fa-solid fa-file-import fa-fw me-1"></i>
                    Importar
                </button>

            </div>

        </div>
    </div>
</div>
