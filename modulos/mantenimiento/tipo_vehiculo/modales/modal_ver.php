<!-- archivo: /modulos/mantenimiento/tipo_vehiculo/modales/modal_ver.php -->

<div id="modalVerVehiculo" class="modal fade" tabindex="-1" aria-labelledby="tituloModalVer" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow rounded-4 border-0">
      <div class="modal-header bg-info text-white rounded-top-4">
        <h5 class="modal-title" id="tituloModalVer">
          <i class="fas fa-eye me-2"></i> Detalles del Vehículo
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body bg-light px-4 py-3" id="contenedorFormularioVer">
        <!-- Aquí se carga el contenido dinámico desde form_view_loader.php -->
        <div class="text-center text-muted py-3">
          <i class="fas fa-spinner fa-spin me-2"></i> Cargando detalles...
        </div>
      </div>

      <div class="modal-footer bg-white rounded-bottom-4 px-4 py-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
