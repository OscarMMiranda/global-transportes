<?php
    // archivo: ModalCrearEntidad.php — modal visual para crear entidad
?>

<div id="modalEntidad" class="modal fade" tabindex="-1" role="dialog">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
      		<div class="modal-header bg-primary" style="color:#fff;">
        		<h4 class="modal-title"><i class="fa fa-plus-circle"></i> Nueva entidad</h4>
      		</div>
      	<div class="modal-body">
        <?php include_once __DIR__ . '/FormEntidad.php'; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="crearEntidad()">
          <i class="fa fa-save"></i> Guardar
        </button>
        <button class="btn btn-default" data-dismiss="modal">
          <i class="fa fa-times"></i> Cancelar
        </button>
      </div>
    </div>
  </div>
</div>