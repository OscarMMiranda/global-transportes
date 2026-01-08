<?php
// archivo: /modulos/usuarios/modales/modal_eliminar.php
?>

<!-- Modal ELIMINAR DEFINITIVO -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel"
     aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">

      <!-- Header -->
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalEliminarLabel">
          <i class="fa fa-exclamation-triangle me-2"></i> Eliminar usuario
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <p class="mb-2">
          ¿Estás seguro de que deseas <strong>eliminar definitivamente</strong> este usuario?
        </p>
        <p class="text-muted small">
          Esta acción es irreversible. El usuario será eliminado de forma permanente del sistema.
        </p>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          Cancelar
        </button>

        <a id="btnConfirmarEliminar" href="#" class="btn btn-danger">
          Eliminar definitivamente
        </a>
      </div>

    </div>
  </div>
</div>
