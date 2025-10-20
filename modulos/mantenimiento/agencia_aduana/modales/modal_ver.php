<?php
	// archivo: /modulos/mantenimiento/agencia_aduana/modales/modal_ver.php
?>

<!-- Modal para ver agencia -->
<div class="modal fade" id="modalVerAgencia" tabindex="-1" role="dialog" aria-labelledby="tituloVerAgencia" aria-hidden="true">
  	<div class="modal-dialog modal-lg modal-dialog-scrollable">
    	<div class="modal-content">
      		<div class="modal-header bg-info text-white">
        		<h5 class="modal-title" id="tituloVerAgencia">
          			<i class="fas fa-eye me-2"></i> Detalle de Agencia
        		</h5>
        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar">
          			<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
      		<div class="modal-body" id="contenedorDetalleAgencia">
        		<!-- Aquí se carga el contenido desde ver.php -->
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
          			<i class="fas fa-times"></i> Cerrar
        		</button>
      		</div>
    	</div>
  	</div>
</div>