<?php
	// archivo: /modulos/asignaciones/modales/modal_editar.php
?>

<div id="modalEditar" class="modal fade" tabindex="-1" role="dialog">
  	<div class="modal-dialog">
    	<div class="modal-content">

      		<form id="formEditar" method="POST">

        		<div class="modal-header">
    <h4 class="modal-title">Editar asignación</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

        		<div class="modal-body">
					<input type="hidden" name="id" id="editar_id">
				
					<div class="form-group">
						<label>Conductor</label>
						<select class="form-control" name="conductor_id" data-role="editar-conductor"></select>
					</div>
				
					<div class="form-group">
						<label>Tracto</label>
						<select class="form-control" name="tracto_id" data-role="editar-tracto"></select>
					</div>

					<div class="form-group">
						<label>Carreta</label>
						<select class="form-control" name="carreta_id" data-role="editar-carreta"></select>
					</div>

					<div class="form-group">
						<label>Inicio</label>
						<input type="datetime-local" class="form-control" name="inicio" id="editar_inicio">
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
