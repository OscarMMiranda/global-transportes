<?php
// archivo: /modulos/usuarios/modales/modal_editar.php
// ------------------------------------------------------
// Modal para editar un usuario existente
// Incluye cambio de contraseña opcional
// ------------------------------------------------------
?>

<!-- MODAL EDITAR USUARIO -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2 modal-title-edit">
                    <i class="bi bi-person-gear"></i>
                    <span>Editar usuario</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- LOADING -->
                <div id="edit_loader" class="loader-edit">
                    <div class="spinner-border text-primary mb-3"></div>
                    <div class="text-muted">Cargando información del usuario…</div>
                </div>

                <p id="edit_info" class="text-muted small d-none">Datos cargados correctamente.</p>

                <!-- FORM -->
                <form id="formEditarUsuario" class="d-none">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="row g-3">

                        <!-- Nombre -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="edit_nombre" name="nombre" placeholder="Nombre">
                                <label>Nombre</label>
                            </div>
                            <div id="error_edit_nombre" class="text-error d-none"></div>
                        </div>

                        <!-- Apellido -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="edit_apellido" name="apellido" placeholder="Apellido">
                                <label>Apellido</label>
                            </div>
                            <div id="error_edit_apellido" class="text-error d-none"></div>
                        </div>

                        <!-- Usuario -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="edit_usuario" name="usuario" placeholder="Usuario">
                                <label>Usuario</label>
                            </div>
                            <div id="error_edit_usuario" class="text-error d-none"></div>
                        </div>

                        <!-- Correo -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="edit_correo" name="correo" placeholder="Correo">
                                <label>Correo electrónico</label>
                            </div>
                            <div id="error_edit_correo" class="text-error d-none"></div>
                        </div>

                        <!-- Rol -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="edit_rol" name="rol">
                                    <option value="">Seleccione un rol</option>
                                </select>
                                <label>Rol</label>
                            </div>
                        </div>

                        <!-- NUEVO: CAMBIO DE CONTRASEÑA -->
                        <div class="col-md-6">
                            <div class="form-floating position-relative">
								<input type="password" id="edit_password" name="password" class="form-control" 
       					placeholder="Nueva contraseña (opcional)" autocomplete="new-password">
								
                                <label>Nueva contraseña (opcional)</label>
								<!-- Botón mostrar/ocultar -->
    <button type="button" class="btn btn-sm btn-outline-secondary toggle-pass"
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
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
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