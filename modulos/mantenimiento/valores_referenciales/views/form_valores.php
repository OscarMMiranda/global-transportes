<!-- Modal -->
<div class="modal fade" id="modalValores" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nuevo Valor Referencial</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Zona -->
        <div class="mb-3">
          <label class="form-label">Zona</label>
          <select name="zona_id" class="form-select" required>
            <option value="">— Seleccione —</option>
            <?php foreach($zonas as $z): ?>
              <option value="<?= $z['id'] ?>">
                <?= htmlspecialchars($z['nombre']) ?>
              </option>
            <?php endforeach ?>
          </select>
        </div>

        <!-- Tipo de Mercadería -->
        <div class="mb-3">
          <label class="form-label">Tipo de Mercadería</label>
          <select name="tipo_mercaderia_id" class="form-select" required>
            <option value="">— Seleccione —</option>
            <?php foreach($tipos as $t): ?>
              <option value="<?= $t['id'] ?>">
                <?= htmlspecialchars($t['nombre']) ?>
              </option>
            <?php endforeach ?>
          </select>
        </div>

        <!-- Año -->
        <div class="mb-3">
          <label class="form-label">Año</label>
          <input 
            type="number" 
            name="anio" 
            class="form-control" 
            value="<?= date('Y') ?>" 
            min="2000" max="<?= date('Y')+1 ?>" 
            required
          >
        </div>

        <!-- Monto -->
        <div class="mb-3">
          <label class="form-label">Monto</label>
          <input 
            type="number" 
            name="monto" 
            class="form-control" 
            step="0.01" 
            placeholder="0.00"
            required
          >
        </div>
      </div>
      <div class="modal-footer">
        <button 
          type="button" 
          class="btn btn-secondary" 
          data-bs-dismiss="modal"
        >
          Cancelar
        </button>
        <button type="submit" class="btn btn-primary">
          Guardar
        </button>
      </div>
    </form>
  </div>
</div>
