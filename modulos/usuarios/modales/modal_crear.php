<?php
// archivo: /modulos/usuarios/modales/modal_crear.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once INCLUDES_PATH . '/funciones.php';
require_once INCLUDES_PATH . '/permisos.php';
require_once __DIR__ . '/../controllers/usuarios_controller.php';

$conn = getConnection();
$roles = obtenerRoles($conn);
?>

<div class="modal fade" id="modalCrearUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-sm border-0">

            <div class="modal-header bg-light border-bottom">
                <h5 class="modal-title fw-semibold d-flex align-items-center">
                    <i class="fa fa-user-plus text-primary me-2"></i>
                    Crear Nuevo Usuario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 py-3">

                <div id="crear-alertas"></div>

                <form id="form-crear-usuario" autocomplete="off">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="usuario" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="correo" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Rol</label>
                            <select name="rol" class="form-select" required>
                                <option value="">Seleccione un rol</option>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?php echo $r['id']; ?>">
                                        <?php echo ucfirst($r['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contraseña</label>
                            <div class="position-relative">
                                <input type="password" name="password" class="form-control" required>
                                <button type="button" class="btn btn-sm btn-outline-secondary toggle-pass"
            data-target="[name='password']"
            style="position:absolute; top:8px; right:10px; z-index:10;">
        <i class="fa fa-eye"></i>
    </button>
                            </div>
                        </div>

                    </div>

                </form>

            </div>

            <div class="modal-footer bg-light border-top">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times me-1"></i> Cancelar
                </button>

                <button type="submit" form="form-crear-usuario" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Guardar Usuario
                </button>

            </div>

        </div>
    </div>
</div>