<?php
    //  archivo :   /modulos/mantenimiento/entidades/views/FormEntidad.php
    //  formulario modular para crear entidad
?>

<form id="formCrearEntidad" class="form-horizontal">
  <div class="form-group">
    <label class="col-sm-3 control-label">Nombre</label>
    <div class="col-sm-9">
      <input type="text" name="nombre" class="form-control" required>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">RUC</label>
    <div class="col-sm-9">
      <input type="text" name="ruc" class="form-control">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Dirección</label>
    <div class="col-sm-9">
      <input type="text" name="direccion" class="form-control">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Tipo de entidad</label>
    <div class="col-sm-9">
      <select name="tipo_id" id="tipo_id" class="form-control" required>
        <option value="">-- Cargando tipos --</option>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Departamento</label>
    <div class="col-sm-9">
      <select name="departamento_id" id="departamento_id" class="form-control" required>
        <option value="">-- Seleccionar --</option>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Provincia</label>
    <div class="col-sm-9">
      <select name="provincia_id" id="provincia_id" class="form-control" required>
        <option value="">-- Seleccionar --</option>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3 control-label">Distrito</label>
    <div class="col-sm-9">
      <select name="distrito_id" id="distrito_id" class="form-control" required>
        <option value="">-- Seleccionar --</option>
      </select>
    </div>
  </div>
</form>