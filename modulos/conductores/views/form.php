<!-- views/form.php -->
<div class="modal fade" id="modalConductor" tabindex="-1">
	<div class="modal-dialog">
    	<form id="frmConductor" class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title">🧍 Nuevo / Editar Conductor</h5>
        		<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      		</div>

      		<div class="modal-body">
        		<input type="hidden" name="id" id="c_id" >

        		<div class="mb-3">
          			<label for="c_nombres" class="form-label">Nombres</label>
          			<input type="text" 
						name="nombres" 
						id="c_nombres" 
						class="form-control" 
						required
						pattern="[A-ZÁÉÍÓÚÑ ]+"
         				maxlength="50"
						>
        		</div>

        		<div class="mb-3">
          			<label for="c_apellidos" class="form-label">Apellidos</label>
          			<input type="text" 
						name="apellidos" 
						id="c_apellidos" 
						class="form-control" 
						required>
        		</div>

        		<div class="mb-3">
          			<label for="c_dni" class="form-label">DNI</label>
          			<input type="text" 
						name="dni" 
						id="c_dni" 
						class="form-control" 
						maxlength="8" 
						pattern="\d{8}" 
						required
						>
        		</div>

        		<div class="mb-3">
          			<label for="c_licencia" 
						class="form-label">
							Licencia de Conducir
					</label>
          			<input type="text" 
						name="licencia_conducir" 
						id="c_licencia_conducir" 
						class="form-control" 
						required
						maxlength="9"
						pattern="[A-Z]{1}\d{8}"
       					>
        		</div>

        		<div class="mb-3">
          			<label for="c_telefono" class="form-label">Teléfono</label>
          			<input type="text" name="telefono" id="c_telefono" class="form-control">
        		</div>

        		<div class="mb-3">
          			<label for="c_correo" class="form-label">Correo Electrónico</label>
          			<input type="email" name="correo" id="c_correo" class="form-control">
        		</div>

        		<div class="form-check">
          			<input class="form-check-input" type="checkbox" id="c_activo" name="activo" value="1" checked>
          			<label class="form-check-label" for="c_activo">Activo</label>
        		</div>
      		</div>

      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        		<button type="submit" class="btn btn-primary">Guardar</button>
      		</div>
    	</form>
  	</div>
</div>
