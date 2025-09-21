<?php
	// Si estás usando este formulario dentro de index.php, asegúrate de que esté dentro de un <div> oculto o modal
?>

<form id="formTipoLugar" method="POST" action="controller.php" class="form-inline" onsubmit="return validarFormulario();">
  	<input type="hidden" name="id" id="tipo_id" value="">

  	<div class="form-group">
    	<label for="nombre">Nombre del tipo:</label>
    	<input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" required>
  	</div>

  	<button type="submit" class="btn btn-primary">
    	<i class="fa fa-save"></i> Guardar
  	</button>
  	<button type="button" class="btn btn-default" onclick="cerrarFormulario()">
    	<i class="fa fa-times"></i> Cancelar
  	</button>
	</form>

	<script>
		function validarFormulario() {
  			var nombre = document.getElementById('nombre').value.trim();
  			if (nombre === '') {
    			alert('El nombre no puede estar vacío.');
    			return false;
  				}
  			return true;
			}

		function cerrarFormulario() {
  			document.getElementById('formulario').style.display = 'none';
			}
	</script>