<!-- archivo: /modulos/papeletas/modales/modal_registrar_pago_profesional.php -->
<div class="modal fade" id="modalRegistrarPago" tabindex="-1" aria-hidden="true" aria-labelledby="modalRegistrarPagoLabel">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content shadow-sm border-0">
      <div class="modal-header bg-white">
        <div class="d-flex align-items-center w-100">
          <div class="me-3">
            <i class="fa fa-file-invoice-dollar fa-2x text-primary"></i>
          </div>
          <div class="flex-grow-1">
            <h5 id="modalRegistrarPagoLabel" class="modal-title mb-0">Registrar pago de papeleta</h5>
            <small id="papeleta_codigo" class="text-muted">Código: —</small>
          </div>
          <div class="text-end">
            <span id="papeleta_estado_badge" class="badge rounded-pill"></span>
          </div>
        </div>
        <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <form id="formRegistrarPago" novalidate>

          <input type="hidden" name="id_papeleta" id="id_papeleta">

          <!-- Resumen financiero -->
          <div class="row g-2 mb-3">
            <div class="col-md-4">
              <label class="form-label small text-uppercase text-muted">Total</label>
              <input type="text" id="pago_monto_total" class="form-control form-control-sm fw-semibold" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label small text-uppercase text-muted">Descuento</label>
              <input type="text" id="pago_monto_descuento" class="form-control form-control-sm" readonly>
            </div>
            <div class="col-md-4">
              <label class="form-label small text-uppercase text-muted">Abonado</label>
              <input type="text" id="pago_monto_abonado" class="form-control form-control-sm" readonly>
            </div>
          </div>

          <div class="row g-2 align-items-center mb-3">
            <div class="col-md-8">
              <label class="form-label small text-uppercase text-muted">Saldo pendiente</label>
              <input type="text" id="pago_saldo_pendiente" class="form-control form-control-lg text-danger fw-bold" readonly>
            </div>
            <div class="col-md-4 text-end">
              <div class="small text-muted">Progreso</div>
              <div class="progress" style="height:10px;">
                <div id="pago_progress" class="progress-bar" role="progressbar" style="width:0%"></div>
              </div>
            </div>
          </div>

          <hr>

          <!-- Formulario de pago -->
          <div class="row g-2 mb-2">
            <div class="col-md-6">
              <label class="form-label">Monto a pagar ahora</label>
              <input type="number" step="0.01" min="0" name="monto_pagado" id="monto_pagado" class="form-control" required aria-describedby="monto_help">
              <div id="monto_help" class="form-text text-muted">No puede exceder el saldo. Use punto para decimales.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Fecha de pago</label>
              <input type="date" name="fecha_pago" id="pago_fecha_pago" class="form-control" required>
            </div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-md-6">
              <label class="form-label">Método</label>
              <select name="metodo" id="pago_metodo" class="form-select" required>
                <option value="">Seleccione</option>
                <option value="Banco">Banco</option>
                <option value="Transferencia">Transferencia</option>
                <option value="Depósito">Depósito</option>
                <option value="Efectivo">Efectivo</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Referencia</label>
              <input type="text" name="referencia" id="pago_referencia" class="form-control" placeholder="Número de operación, voucher, etc.">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Observación</label>
            <textarea name="observacion" id="pago_observacion" class="form-control" rows="2" placeholder="Opcional"></textarea>
          </div>

          <hr>

          <!-- Historial -->
          <h6 class="mb-2">Historial de pagos</h6>
          <div id="contenedorHistorialPagos" class="small text-muted">Cargando...</div>

        </form>
      </div>

      <div class="modal-footer">
        <div class="me-auto small text-muted">Última actualización: <span id="papeleta_ultima_actualizacion">—</span></div>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnRegistrarPago">
          <i class="fa fa-check me-2"></i> Registrar pago
        </button>
      </div>
    </div>
  </div>
</div>

