<?php
// archivo: /modulos/conductores/modales/modal_ver_conductor.php
?>

<div class="modal fade" id="modalVerConductor" tabindex="-1" aria-labelledby="modalVerConductorLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg border-0">

      <!-- Encabezado -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold" id="modalVerConductorLabel">
          <i class="fa fa-id-card me-2"></i> Detalle del Conductor
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Cuerpo -->
      <div class="modal-body">
        <div class="row g-4">

          <!-- Datos personales -->
          <div class="col-md-7">
            <div class="card border-0">
              <div class="card-body">

                <p class="mb-2"><strong>Nombre:</strong> <span id="ver_nombre" class="text-muted"></span></p>
                <p class="mb-2"><strong>DNI:</strong> <span id="ver_dni" class="text-muted"></span></p>
                <p class="mb-2"><strong>Licencia:</strong> <span id="ver_licencia" class="text-muted"></span></p>
                <p class="mb-2"><strong>Teléfono:</strong> <span id="ver_telefono" class="text-muted"></span></p>
                <p class="mb-2"><strong>Correo:</strong> <span id="ver_correo" class="text-muted"></span></p>

                <p class="mb-2"><strong>Dirección:</strong> <span id="ver_direccion" class="text-muted"></span></p>

                <!-- NUEVO: Ubigeo -->
                <p class="mb-2"><strong>Departamento:</strong> <span id="ver_departamento" class="text-muted"></span></p>
                <p class="mb-2"><strong>Provincia:</strong> <span id="ver_provincia" class="text-muted"></span></p>
                <p class="mb-2"><strong>Distrito:</strong> <span id="ver_distrito" class="text-muted"></span></p>

                <p class="mb-2"><strong>Estado:</strong> 
                  <span id="ver_estado" class="badge rounded-pill"></span>
                </p>

              </div>
            </div>
          </div>

          <!-- Foto -->
          <div class="col-md-5 text-center">
            <div class="card border-0">
              <div class="card-body">
                <img id="ver_foto" class="img-fluid rounded shadow-sm mb-2" style="max-height:200px; display:none;" alt="Foto del conductor">
                <div id="sin_foto" class="text-muted fst-italic">Sin foto disponible</div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Pie -->
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fa fa-times"></i> Cerrar
        </button>
      </div>

    </div>
  </div>
</div>
