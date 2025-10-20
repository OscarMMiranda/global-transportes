<?php
// archivo: modales/modal_editar.php
// propósito: modal para editar una ruta entre distritos
?>

<div class="modal fade" id="modalZona" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="index.php">
      <input type="hidden" name="id" value="<?= $registro['id'] ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Ruta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <!-- Zona -->
          <div class="mb-3">
            <label for="zona_id_editar" class="form-label">Zona</label>
            <select name="zona_id" id="zona_id_editar" class="form-select" required>
              <option value="">Seleccione una zona</option>
              <?php foreach ($zonasPadre as $zona): ?>
                <option value="<?= $zona['id'] ?>" <?= $zona['id'] == $registro['zona_id'] ? 'selected' : '' ?>>
                  <?= $zona['nombre'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Origen -->
          <div class="mb-3">
            <label for="origen_id_editar" class="form-label">Distrito de Origen</label>
            <select name="origen_id" id="origen_id_editar" class="form-select" required>
              <option value="">Seleccione distrito de origen</option>
              <?php foreach ($distritos as $d): ?>
                <option value="<?= $d['id'] ?>" <?= $d['id'] == $registro['origen_id'] ? 'selected' : '' ?>>
                  <?= $d['departamento'] ?> / <?= $d['provincia'] ?> / <?= $d['nombre'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Destino -->
          <div class="mb-3">
            <label for="destino_id_editar" class="form-label">Distrito de Destino</label>
            <select name="destino_id" id="destino_id_editar" class="form-select" required>
              <option value="">Seleccione distrito de destino</option>
              <?php foreach ($distritos as $d): ?>
                <option value="<?= $d['id'] ?>" <?= $d['id'] == $registro['destino_id'] ? 'selected' : '' ?>>
                  <?= $d['departamento'] ?> / <?= $d['provincia'] ?> / <?= $d['nombre'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Kilometraje -->
          <div class="mb-3">
            <label for="kilometros_editar" class="form-label">Kilometraje (opcional)</label>
            <input type="number" step="0.01" name="kilometros" id="kilometros_editar" class="form-control"
                   value="<?= htmlspecialchars($registro['kilometros']) ?>" placeholder="Ej. 12.5">
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Actualizar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>