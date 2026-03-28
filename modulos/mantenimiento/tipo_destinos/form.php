<?php
// archivo: /modulos/mantenimiento/tipo_destinos/form.php
// Este archivo ahora solo contiene el formulario que va dentro del modal.
// No debe tener <form> completo, solo los campos.
// El modal principal está en modal_form.php
?>

<input type="hidden" name="id" id="tipo_id">

<div class="form-group">
    <label for="nombre">Nombre del tipo:</label>
    <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" required>
</div>

<div class="form-group">
    <label for="descripcion">Descripción operativa:</label>
    <textarea class="form-control" name="descripcion" id="descripcion" rows="3" maxlength="255"></textarea>
</div>
