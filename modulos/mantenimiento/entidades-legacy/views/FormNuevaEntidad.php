<?php
// archivo: /modulos/mantenimiento/entidades/views/FormNuevaEntidad.php

if (!$isTrashView) {
?>
<div class="modal fade" id="modalNuevaEntidad" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formNuevaEntidad" method="POST">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">
            <i class="fa fa-plus-circle"></i> Registrar nueva entidad
          </h4>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <!-- Nombre -->
              <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
              </div>

              <!-- RUC -->
              <div class="form-group">
                <label for="ruc">RUC:</label>
                <input type="text" id="ruc" name="ruc" maxlength="11" class="form-control">
              </div>

              <!-- Dirección -->
              <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" class="form-control">
              </div>

              <!-- Departamento -->
              <div class="form-group">
                <label for="departamento">Departamento:</label>
                <select id="departamento" name="departamento_id" class="form-control">
                  <?php foreach ($departamentos as $d): ?>
                    <option 
                      value="<?php echo htmlspecialchars($d['id']); ?>" 
                      <?php echo ($d['id'] == 15) ? 'selected' : ''; ?>
                    >
                      <?php echo htmlspecialchars($d['nombre']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Provincia -->
              <div class="form-group">
                <label for="provincia">Provincia:</label>
                <select 
                  id="provincia" 
                  name="provincia_id" 
                  class="form-control" 
                  data-valor="127"
                >
                  <option value="">-- Seleccionar --</option>
                </select>
              </div>

              <!-- Distrito -->
              <div class="form-group">
                <label for="distrito">Distrito:</label>
                <select 
                  id="distrito" 
                  name="distrito_id" 
                  class="form-control" 
                  data-valor="1251"
                >
                  <option value="">-- Seleccionar --</option>
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <!-- Tipo de lugar -->
              <div class="form-group">
                <label for="tipo">Tipo de lugar:</label>
                <select id="tipo" name="tipo_id" class="form-control" required>
                  <option value="">-- Seleccionar --</option>
                  <?php foreach ($tipos as $t): ?>
                    <option value="<?php echo htmlspecialchars($t['id']); ?>">
                      <?php echo htmlspecialchars($t['nombre']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> GUARDAR
          </button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fa fa-times"></i> CANCELAR
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php } ?>