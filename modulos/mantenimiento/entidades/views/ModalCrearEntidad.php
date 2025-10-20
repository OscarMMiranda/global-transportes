<?php
	// 	archivo	: 	/modulos/mantenimiento/views/ModalCrearEntidad.php 
	// 	formulario modal para crear entidad
?>

<div id="modalEntidad" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="tituloCrearEntidad">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
      		<form id="formCrearEntidad" onsubmit="crearEntidad(); return false;">
        		<div class="modal-header bg-success text-white">
          			<h4 class="modal-title"><i class="fa fa-plus-circle"></i> Nueva entidad</h4>
        		</div>
        		<div class="modal-body">
          			<div class="form-group">
            			<label>Nombre</label>
            			<input type="text" name="nombre" class="form-control" required>
          			</div>
          			<div class="form-group">
            			<label>RUC</label>
            			<input type="text" name="ruc" class="form-control" maxlength="11" required>
          			</div>
          			<div class="form-group">
            			<label>Dirección</label>
            			<input type="text" name="direccion" class="form-control" required>
          			</div>
          			<div class="form-group">
            			<label>Tipo de lugar</label>
            			<select name="tipo_id" id="tipo_id" class="form-control" required>
              				<option value="">-- Seleccionar --</option>
            			</select>
          			</div>
          			<div class="form-group">
            			<label>Departamento</label>
            			<select name="departamento_id" id="departamento_id" class="form-control" required>
            		  		<option value="">-- Seleccionar --</option>
            			</select>
          			</div>
          			<div class="form-group">
            			<label>Provincia</label>
            			<select name="provincia_id" id="provincia_id" class="form-select" required>
              				<option value="">-- Seleccionar --</option>
            			</select>
          			</div>
          			<div class="form-group">
            			<label>Distrito</label>
            			<select name="distrito_id" id="distrito_id" class="form-select" required>
              				<option value="">-- Seleccionar --</option>
            			</select>
          			</div>
        		</div>
        		<div class="modal-footer">
          			<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Guardar</button>
          			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
        		</div>
      		</form>
    	</div>
  	</div>
</div>