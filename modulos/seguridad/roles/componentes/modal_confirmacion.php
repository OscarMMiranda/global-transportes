<?php
// archivo: /modulos/seguridad/roles/componentes/modal_confirmacion.php
?>
<div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalConfirmacionTitulo">Confirmar acción</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body" id="modalConfirmacionMensaje">
        ¿Está seguro de continuar?
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" id="btnModalConfirmar">Confirmar</button>
      </div>

    </div>
  </div>
</div>