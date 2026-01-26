<?php
// archivo: /modulos/conductores/modales/modal_conductor.php
?>

<!-- Modal: Crear / Editar Conductor -->
<div class="modal fade" id="modalConductor" data-modo="crear" tabindex="-1" aria-hidden="true">
  	<div class="modal-dialog modal-lg modal-dialog-centered">
    	<div class="modal-content shadow-lg border-0">

      		<!-- Encabezado -->
      		<div class="modal-header bg-primary text-white d-flex align-items-center">
    			<h5 class="modal-title fw-semibold d-flex align-items-center gap-2" id="tituloModalConductor">
        			<i class="fa fa-id-card fs-5"></i>
        			<span>Registrar Conductor</span>
    			</h5>
    			<button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
			</div>

      		<!-- Cuerpo -->
			<div class="modal-body">

        		<!-- FORMULARIO -->
        		<form id="formConductor" enctype="multipart/form-data">
          		<!-- ID oculto -->
          		<input type="hidden" id="c_id" name="id">

          		<!-- FOTO ACTUAL (OBLIGATORIO PARA NO PERDERLA AL EDITAR) -->
				<input type="hidden" id="c_foto_actual" name="foto_actual" value="">

          		<div class="row g-3">

            	<!-- Columna izquierda -->
            	<div class="col-md-7">

              		<div class="row">
                		<div class="col-md-6 mb-3">
                  			<label for="c_nombres" class="form-label">Nombres</label>
                  			<input type="text" class="form-control" id="c_nombres" name="nombres" required>
                		</div>

                <div class="col-md-6 mb-3">
                  <label for="c_apellidos" class="form-label">Apellidos</label>
                  <input type="text" class="form-control" id="c_apellidos" name="apellidos" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="c_dni" class="form-label">DNI</label>
                  <input type="text" class="form-control" id="c_dni" name="dni" maxlength="8" required>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="c_licencia" class="form-label">Licencia de Conducir</label>
                  <input type="text" class="form-control" id="c_licencia" name="licencia_conducir" required>
                </div>
              </div>

              <div class="row">
                <div class="col-md-8 mb-3">
                  <label for="c_correo" class="form-label">Correo</label>
                  <input type="email" class="form-control" id="c_correo" name="correo">
                </div>

                <div class="col-md-4 mb-3">
                  <label for="c_telefono" class="form-label">Teléfono</label>
                  <input type="text" class="form-control" id="c_telefono" name="telefono">
                </div>
              </div>

              <div class="mb-3">
                <label for="c_direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="c_direccion" name="direccion">
              </div>

              <!-- UBIGEO -->
              <div class="row">
                <div class="col-md-4">
                  <label for="departamento_id" class="form-label">Departamento</label>
                  <select id="departamento_id" name="departamento_id" class="form-control" required>
                    <option value="">Seleccione...</option>
                  </select>
                </div>

                <div class="col-md-4">
                  <label for="provincia_id" class="form-label">Provincia</label>
                  <select id="provincia_id" name="provincia_id" class="form-control" required disabled>
                    <option value="">Seleccione...</option>
                  </select>
                </div>

                <div class="col-md-4">
                  <label for="distrito_id" class="form-label">Distrito</label>
                  <select id="distrito_id" name="distrito_id" class="form-control" required disabled>
                    <option value="">Seleccione...</option>
                  </select>
                </div>
              </div>

              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="c_activo" name="activo" checked>
                <label class="form-check-label" for="c_activo">Activo</label>
              </div>

            </div>

            <!-- Columna derecha: Foto -->
            <div class="col-md-5 text-center">

              <label class="form-label">Foto del Conductor</label>

              <div class="mb-2">
                <img id="preview_foto" class="img-fluid rounded shadow-sm"
                     style="max-height: 220px; display:none;" alt="Foto del conductor">
              </div>

              <input type="file" class="form-control" id="c_foto" name="foto" accept="image/*">
              <div class="form-text">Formatos permitidos: JPG y PNG.</div>

            </div>

          </div>

          <!-- Pie del formulario (BOTÓN DENTRO DEL FORM) -->
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fa fa-times"></i> Cancelar
            </button>

            <button type="button" class="btn btn-success" id="btnGuardarConductor">
              <i class="fa fa-save"></i> Guardar
            </button>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>
