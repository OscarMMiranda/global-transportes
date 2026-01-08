<?php
// archivo: /modulos/usuarios/modales/modal_ver.php
// Modal mejorado para ver detalles de un usuario
?>

<div class="modal fade" id="modalVerUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content shadow-sm border-0">

            <!-- Encabezado -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fa fa-user-circle me-2"></i>
                    Información del usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <!-- Cuerpo -->
            <div class="modal-body px-4 py-3" style="max-height: 70vh; overflow-y: auto;">

                <div class="row g-3">

                    <!-- ID -->
                    <div class="col-md-3">
                        <small class="text-muted">ID</small>
                        <div class="fw-semibold placeholder-glow" id="ver-id">
                            <span class="placeholder col-6"></span>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="col-md-3">
                        <small class="text-muted">Estado</small>
                        <div id="ver-estado" class="fw-semibold placeholder-glow">
                            <span class="placeholder col-6"></span>
                        </div>
                    </div>

                    <!-- Usuario -->
                    <div class="col-md-6">
                        <small class="text-muted">Usuario</small>
                        <div class="fw-semibold placeholder-glow" id="ver-usuario">
                            <span class="placeholder col-6"></span>
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div class="col-6">
                        <small class="text-muted">Nombre</small>
                        <div class="fw-semibold placeholder-glow" id="ver-nombre">
                            <span class="placeholder col-8"></span>
                        </div>
                    </div>

                    <!-- Apellido -->
                    <div class="col-6">
                        <small class="text-muted">Apellido</small>
                        <div class="fw-semibold placeholder-glow" id="ver-apellido">
                            <span class="placeholder col-8"></span>
                        </div>
                    </div>

                   

                    <!-- Correo -->
                    <div class="col-md-12">
                        <small class="text-muted">Correo electrónico</small>
                        <div class="fw-semibold placeholder-glow" id="ver-correo">
                            <span class="placeholder col-10"></span>
                        </div>
                    </div>

                    <!-- Rol -->
                    <div class="col-6">
                        <small class="text-muted">Rol</small>
                        <div class="fw-semibold d-flex align-items-center placeholder-glow" id="ver-rol">
                            <span class="placeholder col-6"></span>
                        </div>
                    </div>

                 

                    <!-- Fecha creación -->
                    <div class="col-6">
                        <small class="text-muted">Fecha de creación</small>
                        <div class="fw-semibold placeholder-glow" id="ver-creado">
                            <span class="placeholder col-8"></span>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Pie -->
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>