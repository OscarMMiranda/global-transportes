<?php
// archivo: /modulos/vehiculos/modales/modal_ver_vehiculo.php
?>

<div class="modal fade" id="modalVerVehiculo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">

      <!-- HEADER -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="fa-solid fa-car-side me-2"></i>
          Detalles del Vehículo
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body">

        <!-- Indicador de versión -->
        <div style="background:red;color:white;padding:5px;">MODAL NUEVO</div>

        <!-- Placa -->
        <div class="row mb-3">
          <div class="col-md-4 fw-bold">Placa:</div>
          <div class="col-md-8" id="v_placa">—</div>
        </div>

        <!-- Marca -->
        <div class="row mb-3">
          <div class="col-md-4 fw-bold">Marca:</div>
          <div class="col-md-8" id="v_marca">—</div>
        </div>

        <!-- Modelo -->
        <div class="row mb-3">
          <div class="col-md-4 fw-bold">Modelo:</div>
          <div class="col-md-8" id="v_modelo">—</div>
        </div>

        <!-- Año -->
        <div class="row mb-3">
          <div class="col-md-4 fw-bold">Año:</div>
          <div class="col-md-8" id="v_anio">—</div>
        </div>

        <!-- Tipo -->
        <div class="row mb-3">
          <div class="col-md-4 fw-bold">Tipo:</div>
          <div class="col-md-8" id="v_tipo">—</div>
        </div>

        <!-- Estado -->
        <div class="row mb-3">
          <div class="col-md-4 fw-bold">Estado:</div>
          <div class="col-md-8" id="v_estado">—</div>
        </div>

        <!-- Configuración -->
        <div class="row mb-3">
          <div class="col-md-4 fw-bold">Configuración:</div>
          <div class="col-md-8" id="v_configuracion">—</div>
        </div>

        <!-- Empresa -->
        <div class="row mb-3">
          <div class="col-md-4 fw-bold">Empresa:</div>
          <div class="col-md-8" id="v_empresa">—</div>
        </div>

        <hr>

        <!-- Observaciones -->
        <h6 class="fw-bold mb-3">Observaciones</h6>
        <div class="row mb-3">
          <div class="col-md-12" id="v_observaciones">—</div>
        </div>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer">

        <!-- Botón para ir a la ficha completa -->
        <a id="btnFichaCompleta" href="#" class="btn btn-primary">
          <i class="fa-solid fa-folder-open me-2"></i>
          Ver ficha completa
        </a>

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cerrar
        </button>
      </div>

    </div>
  </div>
</div>