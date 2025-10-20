<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/modales/modal_agregar.php
?>

<div id="modalAgregar" class="modal fade" tabindex="-1" aria-labelledby="tituloModalAgregar" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow rounded-4 border-0">
      
      <div class="modal-header bg-success text-white rounded-top-4">
        <h5 class="modal-title" id="tituloModalAgregar">
          <i class="fas fa-plus me-2"></i> Nuevo Tipo de Vehículo
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body bg-light px-4 py-3" id="contenedorFormularioAgregar">
        <!-- Aquí se carga el formulario dinámico desde form_create.php -->
        <div class="text-center text-muted py-3">
          <i class="fas fa-spinner fa-spin me-2"></i> Cargando formulario...
        </div>
      </div>

      <div class="modal-footer bg-white rounded-bottom-4 px-4 py-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Cancelar
        </button>
      </div>

    </div>
  </div>
</div>