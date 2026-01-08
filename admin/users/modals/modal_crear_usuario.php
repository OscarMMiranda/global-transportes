<?php
// archivo: /admin/users/modals/modal_crear_usuario.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$roles_result = $conn->query("SELECT id, nombre FROM roles");
?>

<div class="modal fade" id="modalCrearUsuario" tabindex="-1" aria-labelledby="labelCrearUsuario" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <!-- IMPORTANTE: ID agregado para que usuarios.js funcione -->
      <form method="post" id="form-crear-usuario" class="form-crear-usuario" autocomplete="off">

        <div class="modal-header">
          <h5 class="modal-title" id="labelCrearUsuario">
            <i class="fas fa-user-plus me-2 text-success"></i> Crear Usuario
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body small">

          <!-- Contenedor para mensajes de error -->
          <div id="crear-alertas"></div>

          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label">Nombre</label>
              <input type="text" name="nombre" class="form-control" required 
                     autocomplete="given-name" autofocus>
            </div>

            <div class="col-md-6">
              <label class="form-label">Apellido</label>
              <input type="text" name="apellido" class="form-control" required 
                     autocomplete="family-name">
            </div>

            <div class="col-md-6">
              <label class="form-label">Usuario</label>
              <input type="text" name="usuario" class="form-control" required 
                     autocomplete="username">
            </div>

            <div class="col-md-6">
              <label class="form-label">Correo</label>
              <input type="email" name="correo" class="form-control" required 
                     autocomplete="email">
            </div>

            <div class="col-md-6">
              <label class="form-label">Rol</label>
              <select name="rol" class="form-select" required autocomplete="off">
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
              <input type="password" name="password" class="form-control" required 
                     autocomplete="new-password">
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