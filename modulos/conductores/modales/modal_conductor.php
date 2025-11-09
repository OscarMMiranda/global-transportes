<?php
// archivo: /modulos/conductores/modales/modal_conductor.php
?>


<div class="modal fade" id="modalConductor" tabindex="-1" aria-labelledby="modalConductorLabel" aria-hidden="true">
  	
	<div class="modal-dialog modal-dialog-centered modal-lg">
	<!-- <div class="modal-dialog modal-lg"> -->
    	
		<form id="frmConductor" class="modal-content" enctype="multipart/form-data" autocomplete="off">
      		
			<div class="modal-header bg-primary text-white">
        		
				<h5 class="modal-title d-flex align-items-center mb-0" id="modalConductorLabel">
          			<i class="fa-solid fa-id-card-clip me-2 text-primary"></i>
          			Ficha del Conductor
        		</h5>
        		<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      		</div>

      		<div class="modal-body">

        		<!-- Campo oculto con label invisible -->
        		<!-- <label for="c_id" class="visually-hidden">ID</label> -->
        		
				<input type="hidden" id="c_id" name="id">
       	 		<div class="row">
          			<div class="col-md-6">
            			<div class="row mb-3">
              				<div class="col-md-6">
            					<label for="c_nombres" class="form-label">
									Nombres
								</label>
            					<input type="text" id="c_nombres" name="nombres" class="form-control" required>
          					</div>
            				<div class="col-md-6">
            					<label for="c_apellidos" class="form-label">
									Apellidos
								</label>
            					<input type="text" id="c_apellidos" name="apellidos" class="form-control" required>
            				</div>
        				</div>
        				<div class="row mb-3">
              				<div class="col-md-4">
                				<label for="c_dni" class="form-label">
									DNI
								</label>
                				<input type="text" id="c_dni" name="dni" class="form-control" required pattern="\d{8}" maxlength="8">
              				</div>
              				<div class="col-md-4">
                				<label for="c_licencia_conducir" class="form-label">
									Licencia Nº
								</label>
                				<input type="text" id="c_licencia_conducir" name="licencia_conducir" class="form-control" required>
            				</div>
            				<div class="col-md-4">
            					<label for="c_telefono" class="form-label">
									Teléfono
								</label>
            					<input type="tel" id="c_telefono" name="telefono" class="form-control">
          					</div>
            			</div>
            			<div class="mb-3">
            				<label for="c_correo" class="form-label">Correo</label>
            				<input type="email" id="c_correo" name="correo" class="form-control">
            			</div>

            			<div class="mb-3">
          					<label for="c_direccion" class="form-label">Dirección</label>
          					<input type="text" id="c_direccion" name="direccion" class="form-control">
        				</div>

            			<div class="form-check mb-3">
              				<input class="form-check-input" type="checkbox" id="c_activo" name="activo" value="1" checked>
              				<label class="form-check-label" for="c_activo">Activo</label>
            			</div>
        			</div>

      				<div class="col-md-6 text-center">
<p class="form-label">Foto actual</p>
            			<img id="preview_foto" src="" alt="Foto del conductor" class="img-fluid rounded border mb-3" style="max-height:180px; display:none;">
            			<div class="mb-3">
          					<label for="c_foto" class="form-label">Nueva foto</label>
              				<input type="file" id="c_foto" name="foto" accept="image/*" class="form-control">
            			</div>
          			</div>
        		</div>
      		</div>

      		<div class="modal-footer bg-light border-0">
        		<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" id="btnCancelar">
        			<i class="fa-solid fa-xmark me-2"></i> Cancelar
    			</button>
    			<button type="submit" class="btn btn-primary">
        			<i class="fa-solid fa-floppy-disk me-2"></i> Guardar
        		</button>
      		</div>
    	</form>
  	</div>
</div>