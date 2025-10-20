<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once __DIR__ . '/../controllers/agencias_controller.php';

$conn = getConnection();
if (!$conn) {
  echo '<div class="alert alert-danger text-center">❌ Error de conexión.</div>';
  return;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
  echo '<div class="alert alert-warning text-center">⚠️ ID inválido.</div>';
  return;
}

$registro      = obtenerRegistro($id);
$departamentos = listarDepartamentos();

if (!$registro || !is_array($registro)) {
  echo '<div class="alert alert-danger text-center">❌ Registro no encontrado.</div>';
  return;
}
?>

<form id="formEditarAgencia" method="post" autocomplete="off">
  <input type="hidden" name="id" value="<?= $registro['id'] ?>">

  <div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" name="nombre" id="nombre" class="form-control form-control-sm" value="<?= htmlspecialchars($registro['nombre']) ?>" required>
  </div>

  <div class="mb-3">
    <label for="ruc" class="form-label">RUC</label>
    <input type="text" name="ruc" id="ruc" class="form-control form-control-sm" value="<?= htmlspecialchars($registro['ruc']) ?>" required>
  </div>

  <div class="mb-3">
    <label for="direccion" class="form-label">Dirección</label>
    <input type="text" name="direccion" id="direccion" class="form-control form-control-sm" value="<?= htmlspecialchars($registro['direccion']) ?>">
  </div>

  <div class="row">
    <div class="col-md-4 mb-3">
      <label for="departamento_id" class="form-label">Departamento</label>
      <select name="departamento_id" id="departamento_id" class="form-select form-select-sm" required>
        <option value="">Seleccione departamento...</option>
        <?php foreach ($departamentos as $dep): ?>
          <option value="<?= $dep['id'] ?>" <?= $dep['id'] == $registro['departamento_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($dep['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-4 mb-3">
      <label for="provincia_id" class="form-label">Provincia</label>
      <select name="provincia_id" id="provincia_id" class="form-select form-select-sm" required>
        <option value="">Seleccione provincia...</option>
      </select>
    </div>

    <div class="col-md-4 mb-3">
      <label for="distrito_id" class="form-label">Distrito</label>
      <select name="distrito_id" id="distrito_id" class="form-select form-select-sm" required>
        <option value="">Seleccione distrito...</option>
      </select>
    </div>
  </div>

  <div class="mb-3">
    <label for="telefono" class="form-label">Teléfono</label>
    <input type="text" name="telefono" id="telefono" class="form-control form-control-sm" value="<?= htmlspecialchars($registro['telefono']) ?>">
  </div>

  <div class="mb-3">
    <label for="correo_general" class="form-label">Correo</label>
    <input type="email" name="correo_general" id="correo_general" class="form-control form-control-sm" value="<?= htmlspecialchars($registro['correo_general']) ?>">
  </div>

  <div class="mb-3">
    <label for="contacto" class="form-label">Contacto</label>
    <input type="text" name="contacto" id="contacto" class="form-control form-control-sm" value="<?= htmlspecialchars($registro['contacto']) ?>">
  </div>
</form>

<script>
$(function () {
  const departamentoId = <?= (int)$registro['departamento_id'] ?>;
  const provinciaId    = <?= (int)$registro['provincia_id'] ?>;
  const distritoId     = <?= (int)$registro['distrito_id'] ?>;

  if (departamentoId) {
    $('#departamento_id').val(departamentoId).trigger('change');

    $.get('/modulos/mantenimiento/agencia_aduana/ajax/provincias_por_departamento.php', { id: departamentoId }, function (data) {
      const opciones = JSON.parse(data).map(p =>
        `<option value="${p.id}" ${p.id == provinciaId ? 'selected' : ''}>${p.nombre}</option>`
      );
      $('#provincia_id').html('<option value="">Seleccione provincia...</option>' + opciones.join(''));

      if (provinciaId) {
        $.get('/modulos/mantenimiento/agencia_aduana/ajax/distritos_por_provincia.php', { id: provinciaId }, function (data) {
          const opciones = JSON.parse(data).map(d =>
            `<option value="${d.id}" ${d.id == distritoId ? 'selected' : ''}>${d.nombre}</option>`
          );
          $('#distrito_id').html('<option value="">Seleccione distrito...</option>' + opciones.join(''));
        });
      }
    });
  }
});
</script>