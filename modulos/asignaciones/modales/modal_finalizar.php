<?php
// archivo: /modulos/asignaciones/modales/modal_finalizar.php
?>

<div id="modalFinalizar" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="formFinalizar" method="POST" autocomplete="off">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Finalizar asignación</h4>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id" id="finalizar_id">
          <p>¿Confirma que desea finalizar esta asignación?</p>
          <div class="form-group">
            <label>Fecha y hora de fin</label>
            <input type="datetime-local" class="form-control" name="fin" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">
            <i class="fa fa-flag-checkered"></i> Finalizar
          </button>
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <i class="fa fa-times"></i> Cancelar
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
