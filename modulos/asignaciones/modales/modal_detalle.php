<?php   
	// archivo: /modulos/asignaciones/modales/modal_detalle.php
?>


<div id="modalDetalle" 
     class="modal fade" 
     tabindex="-1" 
     role="dialog" 
     aria-labelledby="tituloDetalle" 
     aria-hidden="true"
     data-backdrop="static" 
     data-keyboard="false">

    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="tituloDetalle">Detalle de Asignación</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body" id="detalleContenido" style="max-height: 70vh; overflow-y: auto;">
                <div class="text-center text-muted py-4">
                    Cargando información...
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>