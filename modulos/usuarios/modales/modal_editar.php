<?php
// archivo: /modulos/usuarios/modales/modal_editar.php
// ------------------------------------------------------
// Modal para editar un usuario existente
// Incluye cambio de contraseña opcional
// ------------------------------------------------------
?>

<div class="modal fade" id="modalEditarUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-3">

            <!-- HEADER -->
            <div class="modal-header bg-white border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-primary d-flex align-items-center gap-2">
                    <i class="bi bi-person-gear fs-4"></i>
                    Editar usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Línea divisoria elegante -->
            <div class="px-4">
                <hr class="mt-1 mb-1" style="opacity:.15;">
            </div>

            <!-- BODY -->
            <div class="modal-body px-4 pb-4">

                <!-- LOADING -->
                <div id="edit_loader" class="text-center py-4">
                    <div class="spinner-border text-primary mb-3"></div>
                    <div class="text-muted">Cargando información del usuario…</div>
                </div>

                <p id="edit_info" class="text-muted small d-none">Datos cargados correctamente.</p>

                <!-- FORM -->
                <form id="formEditarUsuario" class="d-none">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="row g-4">

                        <!-- Nombre -->
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Nombre</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre">
                            <div id="error_edit_nombre" class="text-error d-none"></div>
                        </div>

                        <!-- Apellido -->
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Apellido</label>
                            <input type="text" class="form-control" id="edit_apellido" name="apellido">
                            <div id="error_edit_apellido" class="text-error d-none"></div>
                        </div>

                        <!-- Usuario -->
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Usuario</label>
                            <input type="text" class="form-control" id="edit_usuario" name="usuario">
                            <div id="error_edit_usuario" class="text-error d-none"></div>
                        </div>

                        <!-- Correo -->
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Correo electrónico</label>
                            <input type="email" class="form-control" id="edit_correo" name="correo">
                            <div id="error_edit_correo" class="text-error d-none"></div>
                        </div>

                        <!-- Rol -->
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Rol</label>
                            <select class="form-select" id="edit_rol" name="rol">
                                <option value="">Seleccione un rol</option>
                            </select>
                        </div>

                        <!-- Contraseña -->
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Nueva contraseña (opcional)</label>
                            <div class="position-relative">
                                <input type="password" id="edit_password" name="password"
                                       class="form-control" autocomplete="new-password">

                                <button type="button"
                                        class="btn btn-sm btn-outline-secondary toggle-pass"
                                        data-target="#edit_password"
                                        style="position:absolute; top:8px; right:10px; z-index:10;">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            <div id="error_edit_password" class="text-error d-none"></div>
                        </div>

                    </div>
                </form>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer bg-white border-top-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button" id="btnGuardarEdicion" class="btn btn-primary d-none">
                    <i class="bi bi-save me-1"></i>
                    Guardar cambios
                </button>
            </div>

        </div>
    </div>
</div>