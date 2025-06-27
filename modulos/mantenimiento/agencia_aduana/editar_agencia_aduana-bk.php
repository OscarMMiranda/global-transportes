<?php
	// 1) Mostrar errores para depuración (desactivar en producción)
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	session_start();
	require_once __DIR__ . '/../../../includes/conexion.php';

	// 2) Verificar conexión y permisos
	if (!$conn) {
	    die("Error en la conexión: " . mysqli_connect_error());
		}
	if (empty($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
	    header('Location: ../sistema/login.php');
	    exit;
		}

	// 3) Inicializar $error y $registro
	$error    = '';
	$registro = [];

	$stmt = $conn->prepare("SELECT id, nombre FROM departamentos ORDER BY nombre");
	$stmt->execute();
	$departamentos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	$stmt->close();


	$stmt = $conn->prepare("SELECT id, nombre, departamento_id FROM provincias ORDER BY nombre");
	$stmt->execute();
	$provincias = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	$stmt->close();

	$stmt = $conn->prepare("SELECT id, nombre, provincia_id FROM distritos ORDER BY nombre");
	$stmt->execute();
	$distritos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	$stmt->close();

	// 4) Cargar datos para edición (GET ?editar=ID)
	if (isset($_GET['editar'])) {
    	$idEdit = (int) $_GET['editar'];
    	$stmt   = $conn->prepare("SELECT * FROM agencias_aduanas WHERE id = ?");
    	$stmt->bind_param("i", $idEdit);
		//$stmt->bind_param("i", $idEdit);
    	$stmt->execute();
    	// $registro = $stmt->get_result()->fetch_assoc() ?: [];
		$registro = $stmt->get_result()->fetch_assoc();
    	$stmt->close();
		}

		// 5.1) Asegurar que $registro tiene todos los índices definidos
		$registro = array_merge(
    		array(
        		'id'              => 0,
        		'nombre'          => '',
        		'ruc'             => '',
        		'direccion'       => '',
        		'departamento_id' => 0,
        		'provincia_id'    => 0,
        		'distrito_id'     => 0,
        		'telefono'        => '',
        		'correo_general'  => '',
        		'contacto'        => '',
    			),
    		(array) $registro
			);

	// 5) Eliminación lógica (GET ?eliminar=ID)
	if (isset($_GET['eliminar'])) {
    	$idDel = (int) $_GET['eliminar'];
    	$stmt  = $conn->prepare("UPDATE agencias_aduanas SET estado = 0 WHERE id = ?");
    	$stmt->bind_param("i", $idDel);
    	$stmt->execute();
    	$stmt->close();
    	header("Location: editar_agencia_aduana.php?msg=eliminado");
    	exit;
		}

	// 6) Reactivar registro (GET ?reactivar=ID)
	if (isset($_GET['reactivar'])) {
    	$idRe = (int) $_GET['reactivar'];
    	$stmt     = $conn->prepare("UPDATE agencias_aduanas SET estado = 1 WHERE id = ?");
    	$stmt->bind_param("i", $idRe);
    	$stmt->execute();
    	$stmt->close();
    	header("Location: editar_agencia_aduana.php?msg=reactivado");
    	exit;
		}
	
	// 8) Procesar formulario
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
		// 8.1) Saneamiento y casteo
		$id              = isset($_POST['id'])              ? (int) $_POST['id']              : 0;
    	$departamento_id = isset($_POST['departamento_id']) ? (int) $_POST['departamento_id'] : 0;
    	$provincia_id    = isset($_POST['provincia_id'])    ? (int) $_POST['provincia_id']    : 0;
    	$distrito_id     = isset($_POST['distrito_id'])     ? (int) $_POST['distrito_id']     : 0;
    	$nombre          = isset($_POST['nombre'])          ? trim($_POST['nombre'])         : '';
    	$ruc             = isset($_POST['ruc'])             ? trim($_POST['ruc'])            : '';
    	$direccion       = isset($_POST['direccion'])       ? trim($_POST['direccion'])      : '';
    	$telefono        = isset($_POST['telefono'])        ? trim($_POST['telefono'])       : '';
    	$correo_general  = isset($_POST['correo_general'])  ? trim($_POST['correo_general']) : '';
    	$contacto        = isset($_POST['contacto'])        ? trim($_POST['contacto'])       : '';


		// Validar campos obligatorios
    	if ($nombre === '' || $ruc === '') {
        	$error = 'Nombre y RUC son obligatorios.';
    		} 
		else {
        	
			// // 7a) Si estamos editando un registro activo existente
			if ($id > 0) {

			// 	 // 8.3) Verificar duplicado de RUC en otro registro
            // 	$chk = $conn->prepare(
            //     	"SELECT id FROM agencias_aduanas WHERE ruc = ? AND id != ?"
            // 		);
            // 	$chk->bind_param("si", $ruc, $id);
            // 	$chk->execute();
            // 	if ($chk->get_result()->num_rows > 0) {
            //     	die('Error: No puedes usar ese RUC porque ya está asignado.');
            // 		}
            // 	$chk->close();


				// UPDATE
            	$upd = $conn->prepare("
                	UPDATE agencias_aduanas 
                	SET 
						nombre = ?, 
						ruc = ?, 
						direccion = ?, 
						telefono = ?, 
						correo_general = ?, 
						contacto = ?
                	WHERE id = ?
            		");
            	$upd->bind_param(
                	"sssiiiisssi",
                	$nombre, $ruc, $direccion,
					$departamento_id, $provincia_id, $distrito_id,
                	$telefono, $correo_general, $contacto,
                	$id
            		);
            	$upd->execute();
            	$upd->close();
            	header("Location: editar_agencia_aduana.php?msg=actualizado");
            	exit;
        		}
			}
			// 7b) Nuevo registro o reactivación (si RUC existe en estado=0)
        	$chk = $conn->prepare(
				"SELECT id FROM agencias_aduanas WHERE ruc = ? AND estado = 0");
        	$chk->bind_param("s", $ruc);
        	$chk->execute();
        	$res = $chk->get_result();

			if ($res->num_rows > 0) {
            	// Reactivar
            	$row         = $res->fetch_assoc();
            	$idReact     = $row['id'];
            	$react       = $conn->prepare("
            	    UPDATE agencias_aduanas
                	SET 
						estado 			= 1,
                  		nombre 			= ?,
                  		direccion       = ?,
                  		departamento_id = ?,
                  		provincia_id    = ?,
                  		distrito_id     = ?,
                  		telefono        = ?,
                  		correo_general  = ?,
                  		contacto        = ?
                	WHERE id = ?
            		");
            	$react->bind_param(
                	"sssiiiisssi",
                	$nombre,
					$direccion,
                	$departamento_id,
					$provincia_id,
					$distrito_id,
					$telefono,
					$correo_general, 
					$contacto,
                	$row['id']
            		);
            	$react->execute();
            	$react->close();
            	header("Location: editar_agencia_aduana.php?msg=reactivado");
            	exit;
        		}
			else {
            	// Insertar nuevo
            	$ins = $conn->prepare("
            	    INSERT INTO agencias_aduanas
              			(nombre, ruc, direccion,
               			departamento_id, provincia_id, distrito_id,
               			telefono, correo_general, contacto)
            		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        			");
            	$ins->bind_param(
            	    "sssiiiisss",
            			$nombre, $ruc, $direccion,
            			$departamento_id, $provincia_id, $distrito_id,
            			$telefono, $correo_general, $contacto
				);
            		if (!$ins->execute()) {
                		die("Error al insertar: " . $ins->error);
            		}
            	$ins->close();
            	header("Location: editar_agencia_aduana.php?msg=agregado");
            	exit;
        		}
		}

	
	$stmtList = $conn->prepare("
    SELECT 
    	a.id, a.nombre, a.ruc, a.direccion, 
      	d.nombre AS departamento_nombre, 
      	p.nombre AS provincia_nombre, 
      	di.nombre AS distrito_nombre, 
      	a.estado, a.fecha_creacion
    FROM agencias_aduanas a
    LEFT JOIN departamentos d ON a.departamento_id = d.id
    LEFT JOIN provincias p ON a.provincia_id = p.id
    LEFT JOIN distritos di ON a.distrito_id = di.id
    ORDER BY a.nombre
	");


	$stmtList->execute();
	$agencias = $stmtList->get_result()->fetch_all(MYSQLI_ASSOC);
	$stmtList->close();

	// 9) Cerrar conexión
	$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
	<head>
    	<meta charset="UTF-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<title>Gestión de Agencias Aduanas</title>
    	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	</head>


	<body class="container mt-4">
    	<h2 class="text-center">Gestión de Agencias Aduanas</h2>
		<div class="mb-4 text-center">
  			<a href="mantenimiento.php" class="btn btn-outline-secondary">
    			← Volver a Mantenimiento
  			</a>
		</div>

		<!-- Mostrar mensaje de error de validación -->
  		<?php if(!empty($error)): ?>
    		<div class="alert alert-danger">
      			<?= htmlspecialchars($error) ?>
    		</div>
  		<?php endif; ?>
	
		<!-- Mensajes de éxito/error -->
    	<?php if (isset($_GET['msg'])): ?>
        	<div class="alert alert-success">
            	<?= htmlspecialchars($_GET['msg']) ?>
        	</div>
    	<?php endif; ?>

		<!-- FORMULARIO DE AGREGAR / EDITAR -->
		<form method="post" action="editar_agencia_aduana.php" class="mb-5">
			<div class="mb-3 d-flex align-items-center">
				<label for="nombre" class="form-label me-2 mb-0" style="width: 200px;">Nombre</label>
				<input 
  					type="text" 
  					class="form-control w-50" 
  					id="nombre" 
  					name="nombre" 
  					value="<?= isset($registro['nombre']) ? htmlspecialchars($registro['nombre']) : '' ?>"
  					required
				>  
			</div>
			<div class="mb-3 d-flex align-items-center">
            	<label for="ruc" class="form-label me-2 mb-0" style="width: 200px;">RUC</label>
            	<input 
					type="text" 
					class="form-control w-50" 
					id="ruc" name="ruc" 
					value="<?= isset ($registro['ruc']) ? htmlspecialchars($registro['ruc']):'' ?>" 
					required>
        	</div>
			<div class="mb-3 d-flex align-items-center">
            	<label for="direccion" class="form-label me-2 mb-0" style="width: 200px;">Dirección</label>
            	<input 
					type="text" 
					class="form-control w-50" 
					id="direccion" 
					name="direccion" 
					value="<?=isset($registro['direccion']) ? htmlspecialchars($registro['direccion']):'' ?>" 
					required>
        	</div>

			<!-- Departamento -->
			<div class="mb-3 d-flex align-items-center">
  				<label for="departamento_id" class="form-label me-2 mb-0" style="width:200px;">
					Departamento
				</label>
  				<div class="col-sm-4 w-50">
    				<select id="departamento" name="departamento_id"  class="form-select w-50" required>
      					<option value="">- Selecciona -</option>
							<?php foreach($departamentos as $d): ?>
								<option value="<?= $d['id'] ?>"
        							<?= $registro['departamento_id']==$d['id']?'selected':''?>>
        							<?= htmlspecialchars($d['nombre']) ?>
      						</option>
						<?php endforeach; ?>
    				</select>
  				</div>
			</div>

			<!-- Provincia -->
			<div class="mb-3 d-flex align-items-center">
  				<label for="provincia_id" class="form-label me-2 mb-0" style="width:200px;">
					Provincia
				</label>
				<!-- <div class="col-sm-4 w-50"></div>				 -->
				<select id="provincia" name="provincia_id" class="form-select w-50" required>
    				<option value="">— Selecciona antes un departamento —</option>
    				<!-- Se rellenará con JS -->
  				</select>
				<!-- </div> -->
			</div>


    	<!-- Distrito -->
		<div class="mb-3 d-flex align-items-center">
  			<label for="distrito_id" class="form-label me-2 mb-0" style="width:200px;">
				Distrito</label>
  			<select id="distrito" name="distrito_id" class="form-select w-50" required>
    			<option value="">— Selecciona antes una provincia —</option>
    			<!-- Se rellenará con JS -->
  			</select>
	</div>
        
        
		      


		<button
      		type="submit"
      		name="<?= isset($registro['id']) && $registro['id']>0 ? 'actualizar' : 'agregar' ?>"
      		class="btn btn-primary"
    	>
      		<?= isset($registro['id']) && $registro['id']>0 ? 'Actualizar Agencia' : 'Agregar Agencia' ?>
    	</button>
		
	</form>
<hr>

<!-- Listado -->
<h2 class="h5 mb-3">Lista de Agencias Aduanas</h2>
<table class="table table-hover align-middle">
    <thead class="table-light">

        <tr>
            <th>Nombre</th>
            <th>RUC</th>
            <th>Dirección</th>
            <th>Distrito</th>
            <th>Provincia</th>
            <th>Departamento</th>
            <th>Estado</th>
            <th>Fecha Creación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($agencias as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['ruc']) ?></td>
                <td><?= htmlspecialchars($row['direccion']) ?></td>
                <td><?= htmlspecialchars($row['distrito_nombre']) ?></td>
                <td><?= htmlspecialchars($row['provincia_nombre']) ?></td>
                <td><?= htmlspecialchars($row['departamento_nombre']) ?></td>
                <td>
                    <?= $row['estado'] 
						? '<span class="badge bg-success">Activo</span>'
                        : '<span class="badge bg-secondary">Eliminado</span>' ?>
                </td>
                <td><?= htmlspecialchars($row['fecha_creacion']) ?></td>
                <td>
                    <?php if($row['estado']): ?>
                        <a href="editar_agencia_aduana.php?editar=<?= $row['id'] ?>" 
							class="btn btn-sm btn-warning">✎</a>
                        <a href="editar_agencia_aduana.php?eliminar=<?= $row['id'] ?>" 
							class="btn btn-sm btn-danger" 
                            onclick="return confirm('¿Eliminar esta agencia?')">🗑</a>
                    <?php else: ?>
                        <a href="editar_agencia_aduana.php?reactivar=<?= $row['id'] ?>" 
							class="btn btn-sm btn-success">⟳</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>




<!-- Incluir jQuery (si aún no lo has hecho) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#departamento").on("change", function(){
    var departamentoId = $(this).val();
    if (departamentoId) {
      $.ajax({
        type: "POST",
        url: "get_provincias.php", // Asegúrate que esta ruta es correcta
        data: { departamento_id: departamentoId },
        success: function(html) {
          $("#provincia").html(html);
          $("#provincia").prop("disabled", false);
        },
        error: function() {
          $("#provincia").html('<option value="">Error al cargar provincias</option>');
        }
      });
    } else {
      $("#provincia").html('<option value="">Elija una provincia</option>');
      $("#provincia").prop("disabled", true);
    }
  });
});
</script>
<!-- Incluir jQuery si aún no está incluido -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#provincia").on("change", function(){
    var provinciaId = $(this).val();
    if (provinciaId) {
      $.ajax({
        type: "POST",
        url: "get_distritos.php", // Asegúrate de que la ruta sea correcta
        data: { provincia_id: provinciaId },
        success: function(html) {
          $("#distrito").html(html);
          $("#distrito").prop("disabled", false);
        },
        error: function() {
          $("#distrito").html('<option value="">Error al cargar distritos</option>');
        }
      });
    } else {
      $("#distrito").html('<option value="">Elija un distrito</option>');
      $("#distrito").prop("disabled", true);
    }
  });
});
</script>

<script>
    $(function(){
      $('#departamento_id').on('change', function(){
        var d = $(this).val();
        $('#provincia_id').html('<option>Cargando…</option>');
        $('#distrito_id').html('<option>— Selecciona una provincia —</option>');
        if (!d) {
          $('#provincia_id').html('<option>— Selecciona un departamento —</option>');
          return;
        }
        $.post('get_provincias.php',{ departamento_id: d })
         .done(function(html){ $('#provincia_id').html(html); })
         .fail(function(){ $('#provincia_id').html('<option>Error</option>'); });
      });

      $('#provincia_id').on('change', function(){
        var p = $(this).val();
        $('#distrito_id').html('<option>Cargando…</option>');
        if (!p) {
          $('#distrito_id').html('<option>— Selecciona una provincia —</option>');
          return;
        }
		$.post('get_distritos.php',{ provincia_id: p })
         .done(function(html){ $('#distrito_id').html(html); })
         .fail(function(){ $('#distrito_id').html('<option>Error</option>'); });
      });

      // Precargar en edición
      if (<?= $registro['departamento_id'] ?> > 0) {
        $('#departamento_id').trigger('change');
        setTimeout(function(){
          $('#provincia_id').val(<?= $registro['provincia_id'] ?>)
                           .trigger('change');
          setTimeout(function(){
            $('#distrito_id').val(<?= $registro['distrito_id'] ?>);
          },150);
        },150);
      }
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
  </script>


</body>
</html>


