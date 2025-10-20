<?php
	// archivo: /modulos/mantenimiento/agencia_aduana/modales/modal_editar.php
?>

<div class="modal fade" id="modalEditarAgencia" tabindex="-1" role="dialog" aria-labelledby="tituloEditarAgencia" aria-hidden="true">
  	<div class="modal-dialog modal-lg modal-dialog-scrollable">
    	<div class="modal-content">
      		<div class="modal-header bg-warning text-dark">
        		<h5 class="modal-title" id="tituloEditarAgencia">
          			<i class="fas fa-edit me-2"></i> Editar Agencia
        		</h5>
        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body" id="contenedorFormularioEditar">
        		<!-- Aquí se carga el formulario desde editar.php -->
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
          			<i class="fas fa-times"></i> Cancelar
        		</button>
        		<button type="submit" form="formEditarAgencia" class="btn btn-primary btn-sm">
          			<i class="fas fa-save"></i> Guardar cambios
        		</button>
      		</div>
    	</div>
  	</div>
</div>