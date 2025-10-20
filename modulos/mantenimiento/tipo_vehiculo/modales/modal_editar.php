<?php
	// 	archivo	: 	/modulos/mantenimiento/tipo_vehiculo/modales/modal_editar.php
?>

<div id="modalEditarVehiculo" class="modal fade" tabindex="-1" aria-labelledby="modalEditarVehiculoLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg modal-dialog-centered">
    	<div class="modal-content shadow rounded-4 border-0">
      		<div class="modal-header bg-primary text-white rounded-top-1">
        		<h5 class="modal-title" id="modalEditarVehiculoLabel">
          			<i class="fas fa-edit me-2"></i> Editar Tipo de Vehículo
        		</h5>
        		<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar">
				</button>
      		</div>

      		<div class="modal-body" id="contenedorFormularioEditar">
				<!-- Aquí se carga el formulario dinámico vía AJAX -->
        		<div class="text-center text-muted py-3">
          			<i class="fas fa-spinner fa-spin me-2"></i> Cargando formulario...
        		</div>
				<div class="modal-footer bg-white rounded-bottom-4 px-4 py-3">
        			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          				<i class="fas fa-times me-1"></i> Cancelar
        			</button>
        			<button type="submit" form="formEditarVehiculo" class="btn btn-success">
          				<i class="fas fa-save me-1"></i> Guardar cambios
        			</button>
      			</div>
      		</div>
    	</div>
  	</div>
</div>