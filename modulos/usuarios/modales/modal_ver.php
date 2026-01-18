<?php
// archivo: /modulos/usuarios/modales/modal_ver.php
// Modal mejorado para ver detalles de un usuario
?>

<div class="modal fade" id="modalVerUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-lg rounded-3">

            <!-- Encabezado -->
            <div class="modal-header bg-white border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-primary d-flex align-items-center">
                    <i class="fa fa-user-circle me-1 fs-4"></i>
                    Información del usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Línea divisoria elegante -->
            <div class="px-4">
                <hr class="mt-1 mb-1" style="opacity: .15;">
            </div>

            <!-- Cuerpo -->
            <div class="modal-body px-4 pb-4" style="max-height: 70vh; overflow-y: auto;">

                <div class="row g-4">

                    <!-- ID -->
                    <div class="col-md-3">
                        <label class="form-label text-muted small mb-1">ID</label>
                        <div class="fw-semibold placeholder-glow" id="ver-id">
                            <span class="placeholder col-6"></span>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="col-md-3">
                        <label class="form-label text-muted small mb-1">Estado</label>
                        <div id="ver-estado" class="fw-semibold placeholder-glow">
                            <span class="placeholder col-6"></span>
                        </div>
                    </div>

                    <!-- Usuario -->
                    <div class="col-md-6">
                        <label class="form-label text-muted small mb-1">Usuario</label>
                        <div class="fw-semibold placeholder-glow" id="ver-usuario">
                            <span class="placeholder col-6"></span>
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div class="col-6">
                        <label class="form-label text-muted small mb-1">Nombre</label>
                        <div class="fw-semibold placeholder-glow" id="ver-nombre">
                            <span class="placeholder col-8"></span>
                        </div>
                    </div>

                    <!-- Apellido -->
                    <div class="col-6">
                        <label class="form-label text-muted small mb-1">Apellido</label>
                        <div class="fw-semibold placeholder-glow" id="ver-apellido">
                            <span class="placeholder col-8"></span>
                        </div>
                    </div>

                    <!-- Correo -->
                    <div class="col-12">
                        <label class="form-label text-muted small mb-1">Correo electrónico</label>
                        <div class="fw-semibold placeholder-glow" id="ver-correo">
                            <span class="placeholder col-10"></span>
                        </div>
                    </div>

                    <!-- Rol -->
                    <div class="col-6">
                        <label class="form-label text-muted small mb-1">Rol</label>
                        <div class="fw-semibold placeholder-glow" id="ver-rol">
                            <span class="placeholder col-6"></span>
                        </div>
                    </div>

                    <!-- Fecha creación -->
                    <div class="col-6">
                        <label class="form-label text-muted small mb-1">Fecha de creación</label>
                        <div class="fw-semibold placeholder-glow" id="ver-creado">
                            <span class="placeholder col-8"></span>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Pie -->
            <div class="modal-footer bg-white border-top-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-light border btn-sm" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>