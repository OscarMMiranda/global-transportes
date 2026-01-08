<?php
// archivo: /modulos/conductores/modales/modal_conductor.php
?>

<!-- Modal: Crear / Editar Conductor -->
<div class="modal fade" id="modalConductor" data-modo="crear" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg border-0">

      <!-- Encabezado -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold" id="tituloModalConductor">
          <i class="fa fa-id-card me-2"></i> Registrar Conductor
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Cuerpo -->
      <div class="modal-body">
        <form id="formConductor" enctype="multipart/form-data">
          <input type="hidden" id="c_id" name="id">

          <div class="row g-3">

            <!-- Columna izquierda -->
            <div class="col-md-7">

              <div class="mb-3">
                <label for="c_nombres" class="form-label">Nombres</label>
                <input type="text" class="form-control" id="c_nombres" name="nombres" required>
              </div>

              <div class="mb-3">
                <label for="c_apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="c_apellidos" name="apellidos" required>
              </div>

              <div class="mb-3">
                <label for="c_dni" class="form-label">DNI</label>
                <input type="text" class="form-control" id="c_dni" name="dni" maxlength="8" required>
                <div class="form-text">Debe contener 8 dígitos numéricos.</div>
              </div>

              <div class="mb-3">
                <label for="c_licencia" class="form-label">Licencia de Conducir</label>
                <input type="text" class="form-control" id="c_licencia" name="licencia_conducir" required>
              </div>

              <div class="mb-3">
                <label for="c_correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="c_correo" name="correo" placeholder="ejemplo@correo.com">
              </div>

              <div class="mb-3">
                <label for="c_telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="c_telefono" name="telefono">
              </div>

              <div class="mb-3">
                <label for="c_direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="c_direccion" name="direccion">
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
        </form>
      </div>

      <!-- Pie -->
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fa fa-times"></i> Cancelar
        </button>

        <button type="submit" form="formConductor" class="btn btn-success" id="btnGuardarConductor">
          <i class="fa fa-save"></i> Crear Conductor
        </button>
      </div>

    </div>
  </div>
</div>