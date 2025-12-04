<!-- Modal para ver detalles del conductor -->
<!-- archivo: /modulos/conductores/modales/modal_ver_conductor.php -->

<div class="modal fade" id="modalVerConductor" tabindex="-1" aria-labelledby="tituloVerConductor" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered modal-lg">
    	
		<div class="modal-content">
      		<div class="modal-header bg-light border-bottom">
        		
				<h5 class="modal-title d-flex align-items-center mb-0" id="tituloVerConductor">
          			<i class="fa-solid fa-id-card-clip me-2 text-primary"></i>
          			Ficha del Conductor
        		</h5>
        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      		</div>

      		<div class="modal-body">
        		<div class="row g-4 align-items-start">
          			<!-- Información del conductor -->
          			<div class="col-md-8">
            			<ul class="list-unstyled mb-0">
              				<li class="mb-2">
                				<i class="fa-solid fa-user text-primary me-2"></i>
                				<strong>Nombre:</strong> <span id="ver_nombre" class="text-secondary">—</span>
              				</li>
              				<li class="mb-2">
                				<i class="fa-solid fa-address-card text-primary me-2"></i>
                				<strong>DNI:</strong> <span id="ver_dni" class="text-secondary">—</span>
              				</li>
              				<li class="mb-2">
                				<i class="fa-solid fa-id-card text-primary me-2"></i>
                				<strong>Licencia:</strong> <span id="ver_licencia" class="text-secondary">—</span>
              				</li>
              				<li class="mb-2">
                				<i class="fa-solid fa-phone text-primary me-2"></i>
                				<strong>Teléfono:</strong> <span id="ver_telefono" class="text-secondary">—</span>
              				</li>
              				<li class="mb-2">
                				<i class="fa-solid fa-envelope text-primary me-2"></i>
                				<strong>Correo:</strong> <span id="ver_correo" class="text-secondary">—</span>
              				</li>
              				<li class="mb-2">
                				<i class="fa-solid fa-location-dot text-primary me-2"></i>
                				<strong>Dirección:</strong> <span id="ver_direccion" class="text-secondary">—</span>
              				</li>
              				<li class="mb-2">
                				<i class="fa-solid fa-circle-info text-primary me-2"></i>
                				<strong>Estado:</strong> <span id="ver_estado" class="badge bg-secondary">—</span>
              				</li>
            			</ul>
          			</div>

          			<!-- Foto del conductor -->
          			<div class="col-md-4 text-center">
            			<img id="ver_foto" src="" alt="Foto del conductor"
              				class="img-fluid rounded shadow-sm border"
              				style="display:none; max-height:200px;">
            			<p id="sin_foto" class="text-muted mt-2" style="display:none;">
              				<i class="fa-regular fa-image me-1"></i> Sin foto disponible
            			</p>
          			</div>
        		</div>
      		</div>
    	</div>
  	</div>
</div>