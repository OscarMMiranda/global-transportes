<!--
    archivo: /modulos/mantenimiento/tipo_destinos/componentes/modal_form.php
    Modal para agregar / editar tipos de destino
-->

<div id="modalTipoDestino" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="formTipoDestino" method="POST" action="controller.php" autocomplete="off">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="modalTitulo">Agregar tipo de destino</h4>
        </div>

        <div class="modal-body">

          <!-- ID oculto -->
          <input type="hidden" name="id" id="tipo_id">

          <!-- Nombre -->
          <div class="form-group">
            <label for="nombre">Nombre del tipo:</label>
            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" required>
          </div>

          <!-- Descripción -->
          <div class="form-group">
            <label for="descripcion">Descripción operativa:</label>
            <textarea class="form-control" name="descripcion" id="descripcion" rows="3" maxlength="255" style="resize:none;"></textarea>
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
