<?php
// archivo: /modulos/vehiculos/modales/modal_agregar_foto.php
?>

<!-- MODAL: AGREGAR FOTO -->
<div class="modal fade" id="modalAgregarFoto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- HEADER -->
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">
          <i class="fa fa-upload me-2"></i> Agregar Foto
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body">

        <!-- ALERTA (oculta por defecto) -->
        <div id="alertaFoto" class="alert alert-danger d-none"></div>

        <form id="formAgregarFoto" enctype="multipart/form-data">

            <!-- ID del vehículo (CORRECTO) -->
            <input type="hidden" id="foto_id_vehiculo" name="foto_id_vehiculo">

            <!-- Archivo -->
            <div class="mb-3">
                <label class="form-label">Archivo (JPG/PNG)</label>
                <input type="file" name="foto" id="foto" class="form-control" accept="image/*" required>
            </div>

            <!-- Descripción -->
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <input type="text" name="descripcion" class="form-control" placeholder="Opcional">
            </div>

        </form>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-success" id="btnGuardarFoto">Guardar</button>
      </div>

    </div>
  </div>
</div>
