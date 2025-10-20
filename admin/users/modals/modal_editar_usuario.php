<?php
// /admin/users/modals/modal_editar_usuario.php

// Depuración defensiva
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log_modal.txt');
error_reporting(E_ALL);

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo '<div class="text-danger text-center p-3">❌ ID inválido</div>';
  exit;
}

$id = intval($_GET['id']);

// Conexión
require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

// Obtener usuario
$sql = "
  SELECT u.id, u.nombre, u.apellido, u.usuario, u.correo, u.rol, r.id AS rol_id
  FROM usuarios u
  JOIN roles r ON u.rol = r.id
  WHERE u.id = $id
  LIMIT 1
";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
  echo '<div class="text-danger text-center p-3">❌ Usuario no encontrado</div>';
  exit;
}

$u = $result->fetch_assoc();
?>

<div class="modal fade" id="modalEditarUsuario<?= $u['id'] ?>" tabindex="-1" aria-labelledby="labelEditarUsuario<?= $u['id'] ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form method="post" class="form-editar-usuario" data-id="<?= $u['id'] ?>">
        <div class="modal-header">
          <h5 class="modal-title" id="labelEditarUsuario<?= $u['id'] ?>">
            <i class="fas fa-user-edit me-2 text-primary"></i> Editar Usuario
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body small">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="nombre<?= $u['id'] ?>" class="form-label">Nombre</label>
              <input type="text" name="nombre" id="nombre<?= $u['id'] ?>" class="form-control" value="<?= htmlspecialchars($u['nombre']) ?>" required>
            </div>

            <div class="col-md-6">
              <label for="apellido<?= $u['id'] ?>" class="form-label">Apellido</label>
              <input type="text" name="apellido" id="apellido<?= $u['id'] ?>" class="form-control" value="<?= htmlspecialchars($u['apellido']) ?>" required>
            </div>

            <div class="col-md-6">
              <label for="correo<?= $u['id'] ?>" class="form-label">Correo</label>
              <input type="email" name="correo" id="correo<?= $u['id'] ?>" class="form-control" value="<?= htmlspecialchars($u['correo']) ?>" required>
            </div>

            <div class="col-md-6">
              <label for="rol<?= $u['id'] ?>" class="form-label">Rol</label>
              <select name="rol" id="rol<?= $u['id'] ?>" class="form-select" required>
                <?php
                  $roles_modal = $conn->query("SELECT id,nombre FROM roles");
                  while ($r = $roles_modal->fetch_assoc()):
                ?>
                  <option value="<?= $r['id'] ?>" <?= $u['rol_id'] == $r['id'] ? 'selected' : '' ?>>
                    <?= ucfirst(htmlspecialchars($r['nombre'])) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Guardar cambios
          </button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i> Cancelar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>