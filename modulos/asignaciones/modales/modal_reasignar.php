<?php
// archivo: /modulos/asignaciones/modales/modal_reasignar.php
?>

<div id="modalReasignar" class="modal fade" tabindex="-1" role="dialog">
  	<div class="modal-dialog">
    	<div class="modal-content">

      		<form id="formReasignar" method="POST">

        		<div class="modal-header">
                    <h4 class="modal-title">Reasignar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

        		<div class="modal-body">

					<input type="hidden" name="id" id="reasignar_id">

					<div class="form-group">
						<label>Conductor</label>
						<select class="form-control" name="conductor_id" data-role="reasignar-conductor"></select>
					</div>

					<div class="form-group">
						<label>Tracto</label>
						<select class="form-control" name="tracto_id" data-role="reasignar-tracto"></select>
					</div>

					<div class="form-group">
						<label>Carreta</label>
						<select class="form-control" name="carreta_id" data-role="reasignar-carreta"></select>
					</div>

				</div>

        		<div class="modal-footer">
          			<button type="submit" class="btn btn-primary">Guardar cambios</button>
          			<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        		</div>

      		</form>

    	</div>
  	</div>
</div>
