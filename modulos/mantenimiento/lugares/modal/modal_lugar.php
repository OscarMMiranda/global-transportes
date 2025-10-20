<!-- Modal Lugar -->
<div class="modal fade" id="modalLugar" tabindex="-1" role="dialog" aria-labelledby="modalLugarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="formLugar">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLugarLabel">Registrar / Editar Lugar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="id_lugar" id="id_lugar">
          <input type="hidden" name="entidad_id_lugar" id="entidad_id_lugar">

          <div class="form-group">
            <label for="nombre_lugar">Nombre del lugar</label>
            <input type="text" class="form-control" name="nombre_lugar" id="nombre_lugar" required>
          </div>

          <div class="form-group">
            <label for="direccion_lugar">Dirección</label>
            <input type="text" class="form-control" name="direccion_lugar" id="direccion_lugar">
          </div>

          <div class="form-group">
            <label for="tipo_id_lugar">Tipo</label>
            <select class="form-control" name="tipo_id" id="tipo_id_lugar" required>
              <option value="">Seleccione tipo</option>
              <!-- opciones cargadas dinámicamente -->
            </select>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="departamento_id">Departamento</label>
              <select class="form-control" name="departamento_id" id="departamento_id" required>
                <option value="">Seleccione departamento</option>
              </select>
            </div>

            <div class="form-group col-md-4">
              <label for="provincia_id">Provincia</label>
              <select class="form-control" name="provincia_id" id="provincia_id" required>
                <option value="">Seleccione provincia</option>
              </select>
            </div>

            <div class="form-group col-md-4">
              <label for="distrito_id">Distrito</label>
              <select class="form-control" name="distrito_id" id="distrito_id" required>
                <option value="">Seleccione distrito</option>
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnGuardarLugar" onclick="guardarLugar()">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>