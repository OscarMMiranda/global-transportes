 <?php
// Vista: form_edit.php
// Variables esperadas: $asignacion (array con datos actuales), $tractos, $remolques, $conductores

if (!isset($asignacion)) {
  echo "<div class='alert alert-danger'>No se encontró la asignación a editar.</div>";
  return;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Asignación</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="container my-5">
  <h3 class="mb-4">✏️ Editar Asignación</h3>

  <form action="index.php?action=update&id=<?= $asignacion['id'] ?>" method="post" class="needs-validation" novalidate>
    
    <!-- Tracto -->
    <div class="mb-3">
      <label for="vehiculo_tracto_id" class="form-label">Tracto</label>
      <select name="vehiculo_tracto_id" id="vehiculo_tracto_id" class="form-select" required>
        <option value="">Seleccione un tracto</option>
        <?php foreach ($tractos as $t): ?>
          <option value="<?= $t['id'] ?>" <?= $t['id'] == $asignacion['vehiculo_tracto_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($t['placa']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <div class="invalid-feedback">Seleccione un tracto válido.</div>
    </div>

    <!-- Remolque -->
    <div class="mb-3">
      <label for="vehiculo_remolque_id" class="form-label">Remolque</label>
      <select name="vehiculo_remolque_id" id="vehiculo_remolque_id" class="form-select" required>
        <option value="">Seleccione un remolque</option>
        <?php foreach ($remolques as $r): ?>
          <option value="<?= $r['id'] ?>" <?= $r['id'] == $asignacion['vehiculo_remolque_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($r['placa']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <div class="invalid-feedback">Seleccione un remolque válido.</div>
    </div>

    <!-- Conductor -->
    <div class="mb-3">
      <label for="conductor_id" class="form-label">Conductor</label>
      <select name="conductor_id" id="conductor_id" class="form-select" required>
        <option value="">Seleccione un conductor</option>
        <?php foreach ($conductores as $c): ?>
          <option value="<?= $c['id'] ?>" <?= $c['id'] == $asignacion['conductor_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['nombres'] . ' ' . $c['apellidos']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <div class="invalid-feedback">Seleccione un conductor válido.</div>
    </div>

    <button type="submit" class="btn btn-primary">
      <i class="fas fa-save me-1"></i> Guardar Cambios
    </button>
    <a href="index.php?action=list" class="btn btn-secondary ms-2">
      <i class="fas fa-arrow-left me-1"></i> Cancelar
    </a>
  </form>
</div>

<!-- JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
  $('#vehiculo_tracto_id, #vehiculo_remolque_id, #conductor_id').select2({ width: '100%' });

  $('form').on('submit', function (e) {
    if (!this.checkValidity()) {
      e.preventDefault();
      e.stopPropagation();
      $(this).addClass('was-validated');
    }
  });
});
</script>
</body>
</html>