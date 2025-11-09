<!-- archivo: modales/modal_valores.php -->
<div class="modal fade" id="modalValores" tabindex="-1" aria-labelledby="modalValoresLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalValoresLabel">Nuevo Valor Referencial</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="zona_id" class="form-label">Zona</label>
          <select name="zona_id" id="zona_id" class="form-select" required>
            <option value="">-- Seleccionar --</option>
            <?php foreach ($zonas as $z): ?>
              <option value="<?= $z['id'] ?>"><?= htmlspecialchars($z['nombre']) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="tipo_mercaderia_id" class="form-label">Tipo de Mercadería</label>
          <select name="tipo_mercaderia_id" id="tipo_mercaderia_id" class="form-select" required>
            <option value="">-- Seleccionar --</option>
            <?php foreach ($tipos as $t): ?>
              <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nombre']) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="anio" class="form-label">Año</label>
          <input type="number" name="anio" id="anio" class="form-control" value="<?= date('Y') ?>" min="2000" required>
        </div>
        <div class="mb-3">
          <label for="monto" class="form-label">Monto</label>
          <input type="number" step="0.01" name="monto" id="monto" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>