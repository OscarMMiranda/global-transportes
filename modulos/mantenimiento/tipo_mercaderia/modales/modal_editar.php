<?php
	// 	archivo	: /modulos/mantenimiento/tipo_mercaderia/modales/modal_editar.php
?>

<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="tituloModalEditar" aria-hidden="true">
  	<div class="modal-dialog modal-lg modal-dialog-centered">
    	<div class="modal-content border-0 shadow">   
      		<div class="modal-header bg-primary text-white">
        		<h5 class="modal-title" id="tituloModalEditar">
          			<i class="fas fa-edit me-2"></i> Editar Mercadería
        		</h5>
        		<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      		</div>
      		<div class="modal-body" id="contenidoModalEditar">
        		<!-- Aquí se carga el formulario vía AJAX -->
        		<div class="text-center text-muted">
          			<i class="fas fa-spinner fa-spin me-2"></i> Cargando formulario...
        		</div>
      		</div>
      		<div class="modal-footer bg-light">
        		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          			<i class="fas fa-times me-1"></i> Cancelar
        		</button>
      		</div>
    	</div>
  	</div>
</div>