<?php
// archivo: /modulos/mantenimiento/tipo_mercaderia/modales/modal_agregar.php
?>

<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="tituloModalAgregar" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="tituloModalAgregar">
          <i class="fas fa-plus-circle me-2"></i> Agregar Tipo de Mercadería
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <form method="post" action="ajax/agregar.php" id="formAgregar" autocomplete="off">
        <div class="modal-body">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="nombre" id="nombre" class="form-control" required maxlength="100">
          </div>

          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="3" maxlength="255"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">
            <i class="fas fa-check-circle me-1"></i> Guardar
          </button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i> Cancelar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>