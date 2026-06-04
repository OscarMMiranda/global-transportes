<?php
// archivo: /modulos/vehiculos/modales/modal_editar_descripcion.php
?>

<!-- MODAL: EDITAR DESCRIPCIÓN DE FOTO -->
<div class="modal fade" id="modalEditarDescripcion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- HEADER -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="fa fa-edit me-2"></i> Editar descripción
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body">

        <!-- ALERTA -->
        <div id="alertaEditarDesc" class="alert alert-danger d-none"></div>

        <!-- ID de la foto -->
        <input type="hidden" id="edit_id_foto">

        <!-- Campo descripción -->
        <div class="mb-3">
          <label class="form-label">Descripción</label>
          <input type="text" id="edit_descripcion" class="form-control" placeholder="Ingrese descripción">
        </div>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" id="btnGuardarDescripcion">Guardar</button>
      </div>

    </div>
  </div>
</div>
