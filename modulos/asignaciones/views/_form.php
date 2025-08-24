<form method="post" action="<?= $action ?>">
  <div class="form-group">
    <label for="conductor_id">Conductor</label>
    <select name="conductor_id" id="conductor_id" class="form-control" required>
      <option value="">--Seleccione--</option>
      <?php foreach ($recursos['conductores'] as $c): ?>
        <option value="<?= $c->getId() ?>"
          <?= isset($asig) && $asig->getConductorId()==$c->getId() ? 'selected' : '' ?>>
          <?= htmlspecialchars($c->getNombre()) ?>
        </option>
      <?php endforeach ?>
    </select>
  </div>

  <div class="form-group">
    <label for="tracto_id">Tracto</label>
    <select name="tracto_id" id="tracto_id" class="form-control" required>
      <option value="">--Seleccione--</option>
      <?php foreach ($recursos['tractos'] as $t): ?>
        <option value="<?= $t->getId() ?>"
          <?= isset($asig) && $asig->getTractoId()==$t->getId() ? 'selected' : '' ?>>
          <?= htmlspecialchars($t->getPlaca()) ?>
        </option>
      <?php endforeach ?>
    </select>
  </div>

  <div class="form-group">
    <label for="carreta_id">Carreta</label>
    <select name="carreta_id" id="carreta_id" class="form-control" required>
      <option value="">--Seleccione--</option>
      <?php foreach ($recursos['carretas'] as $k): ?>
        <option value="<?= $k->getId() ?>"
          <?= isset($asig) && $asig->getCarretaId()==$k->getId() ? 'selected' : '' ?>>
          <?= htmlspecialchars($k->getPlaca()) ?>
        </option>
      <?php endforeach ?>
    </select>
  </div>

  <div class="form-group">
    <label for="fecha_asignacion">Fecha de asignación</label>
    <input type="date" name="fecha_asignacion" id="fecha_asignacion"
      class="form-control"
      value="<?= isset($asig) ? $asig->getFechaAsignacion()->format('Y-m-d') : '' ?>"
      required>
  </div>

  <button type="submit" class="btn btn-primary">Guardar</button>
  <a href="/asignaciones" class="btn btn-secondary">Cancelar</a>
</form>
