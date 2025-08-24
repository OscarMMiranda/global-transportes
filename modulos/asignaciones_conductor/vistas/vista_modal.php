<!-- vista_modal.php -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 
					class="modal-title">
					Confirmar Finalización
				</h5>
				<button 
					type="button" 
					class="btn btn-light d-flex align-items-center gap-1" 
					data-bs-dismiss="modal" 
					aria-label="Cerrar"
				>
    				<i class="fas fa-times"></i> 
					Cerrar
				</button>
      		</div>
      		<div class="modal-body">
        		¿Estás seguro de que deseas finalizar esta asignación?
      		</div>
      		<div class="modal-footer">
				<button 
					type="button" 
					class="btn btn-secondary" 
					data-bs-dismiss="modal">
  					<i class="fas fa-times me-2"></i> 
					Cancelar
				</button>

        		<button
          			type="button"
          			class="btn btn-danger"
          			id="confirmBtn"
        		>
          			Confirmar
        		</button>

				
      		</div>
    	</div>

		


  	</div>
</div>

<!-- Formulario oculto para finalizar asignación -->
		<form 
			id="finalizarForm" 
			method="POST" 
			action="/asignaciones_conductor/finalizar_asignacion.php"
			style="display:none;">
  			<input 
				type="hidden" 
				name="asignacion_id" 
				id="inputAsignacionId">
		</form>
