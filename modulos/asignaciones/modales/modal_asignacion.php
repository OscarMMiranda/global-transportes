<?php
// archivo: /modulos/asignaciones/modales/modal_asignacion.php
?>

<div id="modalAsignar" class="modal fade" tabindex="-1" role="dialog" data-role="modal-asignar">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="formAsignacion" method="POST" autocomplete="off">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nueva asignación</h4>
        </div>

        <div class="modal-body">

          <!-- ID oculto (solo para edición futura si se reutiliza el modal) -->
          <input type="hidden" name="id" id="asignacion_id">

          <!-- CONDUCTOR -->
          <div class="form-group">
            <label>Conductor</label>
            <select class="form-control"
                    name="conductor_id"
                    data-role="conductor"
                    autocomplete="off"
                    required>
              <option value="">Seleccione Conductor</option>
            </select>
          </div>

          <!-- TRACTO -->
          <div class="form-group">
            <label>Tracto</label>
            <select class="form-control"
                    name="tracto_id"
                    data-role="tracto"
                    autocomplete="off"
                    required>
              <option value="">Seleccione Tracto</option>
            </select>
          </div>

          <!-- CARRETA -->
          <div class="form-group">
            <label>Carreta</label>
            <select class="form-control"
                    name="carreta_id"
                    data-role="carreta"
                    autocomplete="off"
                    required>
              <option value="">Seleccione Carreta</option>
            </select>
          </div>

          <!-- FECHA INICIO -->
          <div class="form-group">
            <label>Inicio</label>
            <input type="datetime-local"
                   class="form-control"
                   name="inicio"
                   required>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-save"></i> Guardar
          </button>
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <i class="fa fa-times"></i> Cancelar
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
