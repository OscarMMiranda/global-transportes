<?php
// archivo: editar_form.php

// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../../../includes/config.php';
$conn = getConnection();

// 3) Validación defensiva
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id || !($conn instanceof mysqli)) {
  echo "<div class='alert alert-danger'>❌ ID inválido o conexión fallida.</div>";
  exit;
}

// 4) Consulta
$res = $conn->query("SELECT * FROM tipos_mercaderia WHERE id = $id");
if (!$res || $res->num_rows === 0) {
  echo "<div class='alert alert-warning'>Registro no encontrado.</div>";
  exit;
}

$r = $res->fetch_assoc();
?>

<form method="post" action="ajax/actualizar.php" class="needs-validation" novalidate>
  <input type="hidden" name="id" value="<?= $r['id'] ?>">

  <div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($r['nombre']) ?>" required>
    <div class="invalid-feedback">Este campo es obligatorio.</div>
  </div>

  <div class="mb-3">
    <label for="descripcion" class="form-label">Descripción</label>
    <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required><?= htmlspecialchars($r['descripcion']) ?></textarea>
    <div class="invalid-feedback">Este campo es obligatorio.</div>
  </div>

  <div class="text-end">
    <button type="submit" class="btn btn-primary">
      <i class="fas fa-save me-1"></i> Guardar Cambios
    </button>
  </div>
</form>

<script>
// Validación Bootstrap 5
document.querySelectorAll('.needs-validation').forEach(form => {
  form.addEventListener('submit', e => {
    if (!form.checkValidity()) {
      e.preventDefault();
      e.stopPropagation();
    }
    form.classList.add('was-validated');
  }, false);
});
</script>