<?php
// archivo: modulos/papeletas/modales/modal_ver_historial.php
?>
<!-- Modal Ver Historial - Versión corporativa -->
<div class="modal fade" id="modalVerHistorial" tabindex="-1" aria-labelledby="modalVerHistorialLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-sm">

      <!-- Header -->
      <div class="modal-header bg-white border-bottom py-3">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <rect width="24" height="24" rx="4" fill="#0d6efd"></rect>
              <path d="M8 12h8M8 8h8M8 16h5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div>
            <h5 class="modal-title mb-0" id="modalVerHistorialLabel">Historial de la papeleta</h5>
            <small class="text-muted">Registro de cambios, pagos y acciones</small>
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
        <div id="contenedorHistorialPapeleta" class="p-2">

          <!-- Estado y controles -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="small text-muted">Mostrando historial</div>
            <div class="d-flex gap-2 align-items-center">
              <select id="historial_tipo_filtro" class="form-select form-select-sm" style="width:160px;">
                <option value="">Todos los tipos</option>
                <option value="pago">Pago</option>
                <option value="descuento">Descuento</option>
                <option value="edicion">Edición</option>
                <option value="archivo">Archivo</option>
              </select>
              <button id="historial_refrescar" class="btn btn-outline-secondary btn-sm" title="Refrescar">
                <i class="bi bi-arrow-clockwise"></i>
              </button>
            </div>
          </div>

          <!-- Contenido -->
          <div id="historial_contenido" class="list-group list-group-flush">
            <div class="text-center text-muted py-4" id="historial_cargando">
              <div class="spinner-border text-primary spinner-border-sm me-2" role="status" aria-hidden="true"></div>
              Cargando historial...
            </div>
          </div>

          <!-- Paginación simple -->
          <nav aria-label="Paginación historial" class="mt-3">
            <ul id="historial_paginacion" class="pagination pagination-sm justify-content-center mb-0"></ul>
          </nav>

        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer bg-white border-top py-3">
        <div class="w-100 d-flex justify-content-between align-items-center">
          <div class="text-muted small">Última actualización: <span id="historial_last_update">—</span></div>
          <div>
            <button type="button" class="btn btn-outline-secondary btn-sm me-2" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
