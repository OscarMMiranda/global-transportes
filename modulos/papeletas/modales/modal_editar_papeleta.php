<?php
// archivo: /modulos/papeletas/modales/modal_editar_papeleta.php
?>
<!-- Modal Editar Papeleta - Versión corporativa -->
<div class="modal fade" id="modalEditarPapeleta" tabindex="-1" aria-labelledby="modalEditarPapeletaLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">

      <!-- Header -->
      <div class="modal-header bg-white border-bottom py-3">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <!-- Icono corporativo pequeño -->
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <rect width="24" height="24" rx="4" fill="#0d6efd"></rect>
              <path d="M7 12h10M7 8h10M7 16h6" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div>
            <h5 class="modal-title mb-0" id="modalEditarPapeletaLabel">Gestión de papeleta</h5>
            <small class="text-muted">Editar datos y adjuntos de la papeleta</small>
          </div>
        </div>

        <button type="button" class="btn btn-icon btn-sm btn-light ms-3" data-bs-dismiss="modal" aria-label="Cerrar">
          <span class="visually-hidden">Cerrar</span>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M6 6l12 12M6 18L18 6" stroke="#333" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>

      <!-- Body -->
      <div class="modal-body bg-light">
        <form id="formEditarPapeleta" class="needs-validation" novalidate>

          <!-- Hidden -->
          <input type="hidden" name="edit_id" id="edit_id">
          <input type="hidden" id="editar_papeleta_id">

          <div class="container-fluid py-2">

            <!-- Sección principal -->
            <div class="row g-3 mb-3 align-items-end">
              <div class="col-md-4">
                <label for="edit_codigo_papeleta" class="form-label fw-semibold">Número de papeleta</label>
                <input type="text" class="form-control form-control-sm" id="edit_codigo_papeleta" name="codigo_papeleta" required>
                <div class="invalid-feedback">Ingrese el número de papeleta.</div>
              </div>

              <div class="col-md-4">
                <label for="edit_vehiculo_id" class="form-label fw-semibold">Vehículo</label>
                <select class="form-select form-select-sm" id="edit_vehiculo_id" name="vehiculo_id" required></select>
                <div class="invalid-feedback">Seleccione el vehículo.</div>
              </div>

              <div class="col-md-4">
                <label for="edit_conductor_id" class="form-label fw-semibold">Conductor</label>
                <select class="form-select form-select-sm" id="edit_conductor_id" name="conductor_id"></select>
              </div>
            </div>

            <!-- Emisora / Infracción -->
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label for="edit_entidad_emisora_id" class="form-label fw-semibold">Entidad emisora</label>
                <select class="form-select form-select-sm" id="edit_entidad_emisora_id" name="entidad_emisora_id" required></select>
                <div class="invalid-feedback">Seleccione la entidad emisora.</div>
              </div>

              <div class="col-md-6">
                <label for="edit_infraccion_id" class="form-label fw-semibold">Infracción</label>
                <select class="form-select form-select-sm" id="edit_infraccion_id" name="infraccion_id" required></select>
                <div class="invalid-feedback">Seleccione la infracción.</div>
              </div>
            </div>

            <!-- Fechas -->
            <div class="row g-3 mb-3">
              <div class="col-md-4">
                <label for="edit_fecha_infraccion" class="form-label fw-semibold">Fecha infracción</label>
                <input type="date" class="form-control form-control-sm" id="edit_fecha_infraccion" name="fecha_infraccion" required>
                <div class="invalid-feedback">Ingrese la fecha de la infracción.</div>
              </div>

              <div class="col-md-4">
                <label for="edit_fecha_notificacion" class="form-label fw-semibold">Fecha notificación</label>
                <input type="date" class="form-control form-control-sm" id="edit_fecha_notificacion" name="fecha_notificacion">
              </div>

              <div class="col-md-4">
                <label for="edit_fecha_vencimiento" class="form-label fw-semibold">Fecha vencimiento</label>
                <input type="date" class="form-control form-control-sm" id="edit_fecha_vencimiento" name="fecha_vencimiento">
              </div>
            </div>

            <!-- Lugar y descripción -->
            <div class="row g-3 mb-3">
              <div class="col-md-12">
                <label for="edit_lugar" class="form-label fw-semibold">Lugar</label>
                <input type="text" class="form-control form-control-sm" id="edit_lugar" name="lugar">
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-12">
                <label for="edit_descripcion" class="form-label fw-semibold">Descripción</label>
                <textarea class="form-control form-control-sm" id="edit_descripcion" name="descripcion" rows="3" maxlength="1000"></textarea>
                <div class="form-text text-muted">Máx. 1000 caracteres.</div>
              </div>
            </div>

            <!-- Monto histórico y estado -->
            <div class="row g-3 mb-3">
              <div class="col-md-4">
                <label for="edit_monto" class="form-label fw-semibold">Monto original</label>
                <div class="input-group input-group-sm">
                  <span class="input-group-text">S/</span>
                  <input type="text" class="form-control form-control-sm" id="edit_monto" name="monto" readonly>
                </div>
              </div>

              <div class="col-md-4">
                <label class="form-label fw-semibold">Saldo pendiente</label>
                <div id="edit_saldo" class="form-control form-control-sm bg-white">—</div>
              </div>

              <div class="col-md-4">
                <label class="form-label fw-semibold">Estado</label>
                <div id="edit_estado" class="form-control form-control-sm bg-white">—</div>
              </div>
            </div>

            <!-- Archivos -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <div>
                    <label class="form-label fw-semibold mb-0">Archivos adjuntos</label>
                    <div class="form-text text-muted">Gestiona documentos relacionados a la papeleta.</div>
                  </div>
                  <div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="btnVerArchivosDesdeEditar">
                      <i class="bi bi-folder2-open me-1"></i> Gestionar archivos
                    </button>
                  </div>
                </div>

                <div class="border rounded p-3 bg-white">
                  <div id="lista_archivos_edit" class="small text-muted">No hay archivos cargados.</div>
                </div>
              </div>
            </div>

          </div> <!-- container -->

        </form>
      </div>

      <!-- Footer -->
      <div class="modal-footer bg-white border-top py-3">
        <div class="w-100 d-flex justify-content-between align-items-center">
          <div class="text-muted small">Última modificación: <span id="edit_updated_at">—</span></div>
          <div>
            <button type="button" class="btn btn-outline-secondary btn-sm me-2" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary btn-sm" id="btnActualizarPapeleta">
              <i class="bi bi-save2 me-1"></i> Actualizar
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
