<?php
// archivo: /modulos/mantenimiento/tipo_vehiculo/ajax/form_create.php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error_log.txt');

require_once __DIR__ . '/../../../../includes/config.php';
require_once __DIR__ . '/../modelo/CategoriaModel.php';

$conn = getConnection();
$categoriaModel = new CategoriaModel($conn);
$categorias = $categoriaModel->listar();
?>

<form id="formCrearTipoVehiculo" method="post" action="index.php?action=store" class="needs-validation" novalidate>
  <div class="mb-3">
    <label for="nombre" class="form-label fw-bold">Nombre:</label>
    <input type="text" name="nombre" id="nombre" class="form-control" required maxlength="100">
    <div class="invalid-feedback">⚠️ El nombre es obligatorio.</div>
  </div>

  <div class="mb-3">
    <label for="categoria_id" class="form-label fw-bold">Categoría:</label>
    <select name="categoria_id" id="categoria_id" class="form-select" required>
      <option value="">-- Seleccionar --</option>
      <?php foreach ($categorias as $cat): ?>
        <option value="<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
      <?php endforeach; ?>
    </select>
    <div class="invalid-feedback">⚠️ Seleccioná una categoría válida.</div>
  </div>

  <div class="mb-3">
    <label for="descripcion" class="form-label fw-bold">Descripción:</label>
    <textarea name="descripcion" id="descripcion" class="form-control" rows="3" maxlength="255"></textarea>
  </div>

  <div class="text-end">
    <button type="submit" class="btn btn-success">
      <i class="fas fa-save me-1"></i> Guardar
    </button>
  </div>
</form>

<script>
// Validación visual Bootstrap 5
(function () {
  const form = document.getElementById('formCrearTipoVehiculo');
  form.addEventListener('submit', function (event) {
    if (!form.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();
    }
    form.classList.add('was-validated');
  }, false);
})();
</script>

<?php $conn->close(); ?>