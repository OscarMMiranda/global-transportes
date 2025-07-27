<!-- sólo el modal, sin <link> ni <script> ni tabla -->
<div 
  	class="modal fade" 
  	id="modalAsignar" 
  	tabindex="-1" 
  	aria-labelledby="modalAsignacionLabel" 
  	aria-hidden="true"
>
  	<div class="modal-dialog modal-lg modal-dialog-centered">
    	<div class="modal-content shadow-lg border-0">
      		<form id="formAsignacion" class="needs-validation" novalidate>
        		<div class="modal-header bg-success text-white">
          			<h5 class="modal-title" id="modalAsignacionLabel">
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
              				data-role="conductor" 
              				name="conductor_id" 
              				class="form-select" 
              				required
            			>
              				<option value="">Cargando conductores...</option>
            			</select>
            			<div class="invalid-feedback">
              				Selecciona un conductor.
            			</div>
          			</div>

          			<div class="col-md-4">
            			<label for="vehiculo_tracto_id" 
							class="form-label"
						>
							Tracto
						</label>
            			<select 
              				data-role="tracto" 
              				name="tracto_id" 
              				class="form-select" 
              				required
            			>
              				<option value="">Cargando tractos...</option>
            			</select>
            			<div class="invalid-feedback">
              				Selecciona un tracto.
            			</div>
          			</div>

          			<div class="col-md-4">
            			<label for="carreta_id" class="form-label">Carreta</label>
            			<select 
              				data-role="carreta" 
              				name="carreta_id" 
              				class="form-select" 
              				required
            			>
              <option value="">Cargando carretas...</option>
            </select>
            <div class="invalid-feedback">
              Selecciona una carreta.
            </div>
          </div>

          <div class="col-md-4">
            <label for="fecha_inicio" class="form-label">
              Fecha Inicio
            </label>
            <input 
              type="date"
              id="fecha_inicio" 
              name="fecha_inicio" 
              class="form-control" 
              required
            >
            <div class="invalid-feedback">
              Ingresa la fecha de inicio.
            </div>
          </div>

          <div class="col-12">
            <label for="observaciones" class="form-label">
              Observaciones
            </label>
            <textarea 
              id="observaciones" 
              name="observaciones" 
              class="form-control"
            ></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
