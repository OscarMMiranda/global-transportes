<!-- form_asignacion.php -->

<?php
    //include_once 'form_asignacion.php';
?>


<!-- CSS de Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


<h2>Asignaciones – Conductor, Tracto y Carreta</h2>

<button 
    class="btn btn-success mb-3" 
    data-bs-toggle="modal" 
    data-bs-target="#modalAsignar">
    ➕ Nueva Asignación
</button>

<table id="tablaAsignaciones" class="table table-striped table-hover">
  	<thead>
    	<tr>
      		<th>Conductor</th>
      		<th>Tracto</th>
      		<th>Carreta</th>
      		<th>Inicio</th>
      		<th>Fin</th>
      		<th>Estado</th>
      		<th>Acción</th>
    	</tr>
  	</thead>
  	<tbody>

	</tbody>
</table>

<script>
  	window.ASIGNACIONES_API_URL = '/modulos/asignaciones/api.php';
</script>
<script src="asignaciones.js"></script>


<div 
	class="modal fade" 
	id="modalAsignar" 
	tabindex="-1" 
	aria-labelledby="modalAsignacionLabel" 
	aria-hidden="true"
>
  	<div class="modal-dialog modal-lg modal-dialog-centered">
    	<!-- <div class="modal-content"> -->
		<div class="modal-content shadow-lg border-0">
      		<form id="formAsignacion" class="p-3 bg-light rounded">
        		
				<div 
					class="modal-header">
          			<h5 
						class="modal-title"
						id="modalAsignacionLabel">
						Nueva Asignación
					</h5>
          			<button 
						type="button" 
						class="btn-close btn-close-white" 
						data-bs-dismiss="modal"
						aria-label="Cerrar"
					>
					</button>
        		</div>

        		<div class="modal-body row g-3 px-3">
          			
					<div class="col-md-4">
            			<label 
							for="conductor_id"
							class="form-label"
						>
							Conductor
						</label>
            			<select 
							id="conductor_id" 
							name="conductor_id" 
							class="form-select" 
							required>
							<option value="">Cargando conductores...</option>
						</select>
						<div class="invalid-feedback">Selecciona un conductor.</div>
          			</div>

          			<div class="col-md-4">
            			<label for="vehiculo_tracto_id" class="form-label">
							Tracto
						</label>
            			<select 
							id="vehiculo_tracto_id" 
							name="vehiculo_tracto_id" 
							class="form-select" 
							required>
							<option value="">Cargando tractos...</option>
						</select>
						<div class="invalid-feedback">Selecciona un tracto.</div>
          			</div>

          			<div class="col-md-4">
            			<label for="vehiculo_carreta_id" class="form-label">
							Carreta
						</label>
            			<select 
							id="vehiculo_carreta_id" 
							name="vehiculo_carreta_id" 
							class="form-select" 
							required>
							<option value="">Cargando carretas...</option>
						</select>
						<div class="invalid-feedback">Selecciona una carreta.</div>
          			</div>

          			<div class="col-md-4">
            			<label 
							for="fecha_inicio" 
							class="form-label">
							Fecha Inicio
						</label>
            			<input 
							type="date"
							id="fecha_inicio" 
							name="fecha_inicio" 
							class="form-control" 
							required>
						<div class="invalid-feedback">Ingresa la fecha de inicio.</div>
          			</div>
          			<div class="col-12">
            			<label>Observaciones</label>
            			<textarea name="observaciones" class="form-control"></textarea>
          			</div>
        		</div>
        		<div class="modal-footer">
        	  		<button 
						class="btn btn-primary" 
						type="submit">
						Guardar
					</button>
        		</div>
      		</form>
    	</div>
  	</div>
</div>
