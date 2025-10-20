<?php
// archivo: modales/modal_agregar.php
// propósito: formulario modal para agregar ruta entre distritos

if (!isset($registro)) {
  $registro = ['id' => 0, 'zona_id' => 0, 'origen_id' => 0, 'destino_id' => 0, 'kilometros' => ''];
}
?>

<div class="modal fade" id="modalAgregar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="index.php" id="formAgregarRuta">
      <input type="hidden" name="id" value="0">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Agregar Ruta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <!-- Zona -->
          <div class="mb-3">
            <label for="zona_id" class="form-label">Zona</label>
            <select name="zona_id" id="zona_id" class="form-select" required>
              <option value="">Seleccione una zona</option>
              <?php foreach ($zonasPadre as $zona): ?>
                <option value="<?= $zona['id'] ?>"><?= $zona['nombre'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Origen -->
          <div class="mb-3">
            <label for="origen_id" class="form-label">Distrito de Origen</label>
            <select name="origen_id" id="origen_id" class="form-select" required>
              <option value="">Seleccione distrito de origen</option>
              <?php foreach ($distritos as $d): ?>
                <option value="<?= $d['id'] ?>"><?= $d['departamento'] ?> / <?= $d['provincia'] ?> / <?= $d['nombre'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Destino -->
          <div class="mb-3">
            <label for="destino_id" class="form-label">Distrito de Destino</label>
            <select name="destino_id" id="destino_id" class="form-select" required>
              <option value="">Seleccione distrito de destino</option>
              <?php foreach ($distritos as $d): ?>
                <option value="<?= $d['id'] ?>"><?= $d['departamento'] ?> / <?= $d['provincia'] ?> / <?= $d['nombre'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Kilometraje -->
          <div class="mb-3">
            <label for="kilometros" class="form-label">Kilometraje (opcional)</label>
            <input type="number" step="0.01" name="kilometros" id="kilometros" class="form-control"
                   value="" placeholder="Ej. 12.5">
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- 🛡️ Validación visual -->
<script>
document.getElementById('formAgregarRuta').addEventListener('submit', function(e) {
  const zona = document.getElementById('zona_id').value;
  const origen = document.getElementById('origen_id').value;
  const destino = document.getElementById('destino_id').value;

  if (!zona || !origen || !destino) {
    alert('❌ Todos los campos son obligatorios.');
    e.preventDefault();
    return;
  }

  if (origen === destino) {
    alert('❌ El origen y destino no pueden ser iguales.');
    e.preventDefault();
  }
});
</script>