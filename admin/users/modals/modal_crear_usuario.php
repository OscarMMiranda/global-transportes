<?php
// archivo: /admin/users/modals/modal_crear_usuario.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$roles_result = $conn->query("SELECT id, nombre FROM roles");
?>

<div class="modal fade" id="modalCrearUsuario" tabindex="-1" aria-labelledby="labelCrearUsuario" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form method="post" class="form-crear-usuario">
        <div class="modal-header">
          <h5 class="modal-title" id="labelCrearUsuario">
            <i class="fas fa-user-plus me-2 text-success"></i> Crear Usuario
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body small">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre</label>
              <input type="text" name="nombre" class="form-control" required autofocus>
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
              <label class="form-label">Correo</label>
              <input type="email" name="correo" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Rol</label>
              <select name="rol" class="form-select" required>
                <option value="">-- Selecciona un rol --</option>
                <?php while ($row = $roles_result->fetch_assoc()): ?>
                  <option value="<?= $row['id'] ?>">
                    <?= ucfirst(htmlspecialchars($row['nombre'])) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Contraseña</label>
              <input type="password" name="clave" class="form-control" required>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i> Crear usuario
          </button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i> Cancelar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>