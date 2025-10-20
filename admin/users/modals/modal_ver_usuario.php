<?php
// archivo: /admin/users/modals/modal_ver_usuario.php

// Depuración defensiva
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log_ver.txt');
error_reporting(E_ALL);

// Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo '<div class="text-danger text-center p-3">❌ ID inválido</div>';
  exit;
}

$id = intval($_GET['id']);
require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

// Consulta trazable con JOIN para mostrar nombre del eliminador
$sql = "
  SELECT 
    u.id, u.nombre, u.apellido, u.usuario, u.correo,
    r.nombre AS rol,
    u.creado_en, u.eliminado, u.eliminado_por, u.eliminado_en,
    e.usuario AS nombre_eliminador
  FROM usuarios u
  JOIN roles r ON u.rol = r.id
  LEFT JOIN usuarios e ON u.eliminado_por = e.id
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

<div class="modal fade" id="modalVerUsuario<?= $u['id'] ?>" tabindex="-1" aria-labelledby="labelVerUsuario<?= $u['id'] ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="labelVerUsuario<?= $u['id'] ?>">
          <i class="fas fa-eye me-2 text-secondary"></i> Detalles del Usuario
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body small">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($u['nombre']) ?>" readonly>
          </div>

          <div class="col-md-6">
            <label class="form-label">Apellido</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($u['apellido']) ?>" readonly>
          </div>

          <div class="col-md-6">
            <label class="form-label">Usuario</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($u['usuario']) ?>" readonly>
          </div>

          <div class="col-md-6">
            <label class="form-label">Correo</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($u['correo']) ?>" readonly>
          </div>

          <div class="col-md-6">
            <label class="form-label">Rol</label>
            <input type="text" class="form-control" value="<?= ucfirst(htmlspecialchars($u['rol'])) ?>" readonly>
          </div>

          <div class="col-md-6">
            <label class="form-label">Fecha de creación</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($u['creado_en']) ?>" readonly>
          </div>

          <?php if ($u['eliminado']): ?>
          <div class="col-md-6">
            <label class="form-label text-danger">Eliminado por</label>
            <input type="text" class="form-control text-danger" value="<?= htmlspecialchars($u['nombre_eliminador'] ?: '—') ?>" readonly>
          </div>

          <div class="col-md-6">
            <label class="form-label text-danger">Fecha de eliminación</label>
            <input type="text" class="form-control text-danger" value="<?= htmlspecialchars($u['eliminado_en'] ?: '—') ?>" readonly>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>