<?php
	// 🔐 Blindaje extremo y trazabilidad
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/error_log.txt');

	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
	$conn = getConnection();

	if (session_status() === PHP_SESSION_NONE) {
    	session_start();
		}

	// 🔍 Validar sesión
	if (empty($_SESSION['usuario'])) {
  		echo '<div class="alert alert-danger text-center">❌ Sesión no válida.</div>';
  		error_log("❌ Intento de acceso sin sesión en form_create.php");
  		return;
		}

	// 📋 Formulario modular
?>

	<form id="formNuevaAgencia" method="post" action="/modulos/mantenimiento/agencia_aduana/index.php?action=store" class="needs-validation" novalidate>

  		<div class="mb-2 row align-items-center">
    	
			<!-- Nombre -->
    		<div class="col-md-8">
      			<label for="nombre" class="form-label w-100">Nombre :</label>
      			<input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" autocomplete="organization" required aria-describedby="nombreHelp">
      			<div id="nombreHelp" class="form-text">
					Ej: Agencia Transporte Lima. Máx. 100 caracteres.
				</div>
      			<div class="invalid-feedback">
					Por favor ingresa un nombre válido (2–100 caracteres).
				</div>
    		</div>

    		<!-- RUC -->
    		<div class="col-md-4">
    	  		<label for="ruc" class="form-label w-100">RUC</label>
    	  		<input type="text" class="form-control" name="ruc" id="ruc" required pattern="\d{11}" maxlength="11" inputmode="numeric" title="Debe tener 11 dígitos numéricos">
    	  		<div class="form-text">Debe tener 11 dígitos.</div>
    	  		<div class="invalid-feedback">
					RUC inválido. Debe tener 11 dígitos numéricos.
				</div>
    		</div>
  		</div>

      	<!-- DIRECCION -->
  		<div class="mb-2">
    		<label for="direccion" class="form-label">Dirección</label>
    		<!-- <input type="text" class="form-control" name="direccion" id="direccion" required> -->
			<input type="text" class="form-control" name="direccion" id="direccion" required pattern="^(?!0$).+" title="La dirección no puede ser '0'" autocomplete="off">

  		</div>

  		<div class="mb-3 row align-items-center">
    		
			<!-- Departamento -->
    		<div class="col-md-4">
      			<label for="departamento" class="form-label">Departamento</label>
      			<select class="form-select" name="departamento_id" id="departamento" required>
        			<option value="">Seleccione...</option>
      			</select>
      			<div class="invalid-feedback">Seleccione un departamento válido.</div>
    		</div>

    		<!-- Provincia -->
    		<div class="col-md-4">
      			<label for="provincia" class="form-label">Provincia</label>
      			<select class="form-select" name="provincia_id" id="provincia" required>
        			<option value="">Seleccione...</option>
      			</select>
      			<div class="invalid-feedback">Seleccione una provincia válida.</div>
    		</div>

    		<!-- Distrito -->
    		<div class="col-md-4">
      			<label for="distrito" class="form-label">Distrito</label>
      			<select class="form-select" name="distrito_id" id="distrito" required>
       	 			<option value="">Seleccione...</option>
      			</select>
      			<div class="invalid-feedback">Seleccione un distrito válido.</div>
    		</div>
  		</div>

  		<div class="mb-3 row align-items-center">
    		<!-- Usuario creador -->
    		<div class="col-md-6">
      			<label for="usuario_creacion" class="form-label">Usuario creador</label>
      			<input type="text" class="form-control" name="usuario_creacion" id="usuario_creacion" value="<?php echo htmlspecialchars($_SESSION['usuario']); ?>" readonly>
    		</div>

    		<!-- IP de registro -->
    		<div class="col-md-6">
      			<label for="ip_registro" class="form-label">IP de registro</label>
      			<input type="text" class="form-control" name="ip_registro" id="ip_registro" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" readonly>
    		</div>
  		</div>

  		<div class="mb-3">
    		<label for="observaciones" class="form-label">Observaciones</label>
    		<textarea class="form-control" name="observaciones" id="observaciones" rows="2"></textarea>
  		</div>

    	<div class="text-end">
    		<button type="submit" class="btn btn-success">
    			<i class="fas fa-save me-1"></i> Guardar agencia
    		</button>
    		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
      			<i class="fas fa-times me-1"></i> Cancelar
    		</button>
  		</div>
	</form>

	<script>
		document.getElementById("formNuevaAgencia").addEventListener("submit", function() {
  		const direccion = document.getElementById("direccion").value;
  		console.log("🧪 Dirección enviada:", direccion);
  		if (direccion === "0") {
    		console.warn("⚠️ Dirección inválida detectada antes de enviar.");
  			}
		});
	</script>



	<script>
	// ✅ Validación visual Bootstrap 5
	(() => {
	const form = document.getElementById("formNuevaAgencia");
  	form.addEventListener("submit", event => {
    	if (!form.checkValidity()) {
      		event.preventDefault();
      		event.stopPropagation();
    		}
    form.classList.add("was-validated");
  		}, false);
		})();

	// 🔄 Carga dinámica de departamentos, provincias y distritos
	$(document).ready(function() {
  		$.ajax({
    		url: '/modulos/mantenimiento/agencia_aduana/ajax/departamentos.php',
    		type: 'GET',
    		success: function(data) {
      		$('#departamento').html(data);
    	},
    error: function(xhr, status, error) {
      	console.error('Error al cargar departamentos:', error);
    	}
  	});

  $('#departamento').on('change', function() {
    var departamento_id = $(this).val();
    $('#provincia').html('<option value="">Cargando...</option>');
    $('#distrito').html('<option value="">Seleccione...</option>');

    $.ajax({
      url: '/modulos/mantenimiento/agencia_aduana/ajax/provincias.php',
      type: 'POST',
      data: { departamento_id: departamento_id },
      success: function(data) {
        $('#provincia').html(data);
      },
      error: function(xhr, status, error) {
        console.error('Error al cargar provincias:', error);
      }
    });
  });

  $('#provincia').on('change', function() {
    var provincia_id = $(this).val();
    $('#distrito').html('<option value="">Cargando...</option>');

    $.ajax({
      url: '/modulos/mantenimiento/agencia_aduana/ajax/distritos.php',
      type: 'POST',
      data: { provincia_id: provincia_id },
      success: function(data) {
        $('#distrito').html(data);
      },
      error: function(xhr, status, error) {
        console.error('Error al cargar distritos:', error);
      }
    });
  });
});
</script>