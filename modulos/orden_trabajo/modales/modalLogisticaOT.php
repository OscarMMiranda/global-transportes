<!-- Modal Logística OT 
    Archivo: /modulos/orden_trabajo/modales/modalLogisticaOT.php
-->
    
<div class="modal fade" id="modalLogisticaOT" tabindex="-1" role="dialog" aria-labelledby="modalLogisticaOTLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLogisticaOTLabel">Logística - Registrar / Editar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <!-- Hidden OT id -->
        <input type="hidden" id="log_ot_id" name="log_ot_id" value="">

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="log_vehiculo_id">Vehículo</label>
            <select id="log_vehiculo_id" class="form-control">
              <option value="">Seleccione vehículo</option>
            </select>
          </div>

          <div class="form-group col-md-6">
            <label for="log_conductor_id">Conductor</label>
            <select id="log_conductor_id" class="form-control">
              <option value="">Seleccione conductor</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="log_entidad_id">Entidad</label>
            <select id="log_entidad_id" class="form-control">
              <option value="">Seleccione entidad</option>
            </select>
          </div>

          <div class="form-group col-md-6">
            <label for="log_ruta_id">Ruta</label>
            <select id="log_ruta_id" class="form-control">
              <option value="">Seleccione ruta</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="log_fecha_salida">Fecha salida</label>
            <input type="datetime-local" id="log_fecha_salida" class="form-control" />
          </div>

          <div class="form-group col-md-6">
            <label for="log_fecha_llegada">Fecha llegada</label>
            <input type="datetime-local" id="log_fecha_llegada" class="form-control" />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="log_km_inicial">KM inicial</label>
            <input type="number" id="log_km_inicial" class="form-control" />
          </div>

          <div class="form-group col-md-6">
            <label for="log_km_final">KM final</label>
            <input type="number" id="log_km_final" class="form-control" />
          </div>
        </div>

        <div class="form-group">
          <label for="log_observaciones">Observaciones</label>
          <textarea id="log_observaciones" class="form-control" rows="3"></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" id="btnGuardarLogistica" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
