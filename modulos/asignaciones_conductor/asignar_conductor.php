<?php
	session_start();

	// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 	echo json_encode(["success" => true, "message" => "PHP responde correctamente"]);
    // 	exit();
	// 	}

// 2) Modo depuración (solo DEV)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors',     1);
    ini_set('error_log',      __DIR__ . '/error_log.txt');

    // 3) Cargar config.php (define getConnection() y rutas)
    require_once __DIR__ . '/../../includes/config.php';

    // 4) Obtener la conexión
    $conn = getConnection();


// Verificar que el usuario sea administrador
	if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
		header("Location: http://www.globaltransportes.com/login.php");
		exit();
		}

// Función de respuesta JSON para solicitudes AJAX
	function respondJSON($success, $message) {
    	header('Content-Type: application/json');
    	echo json_encode(["success" => $success, "message" => $message]);
		exit();
		}

// Detectamos si la solicitud es AJAX
	$isAjax = false;
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$isAjax = true;
		}

// OBTENER EL ID DEL ESTADO "ACTIVO" (para asignaciones)
	$sql_estado_activo = "SELECT id FROM estado_asignacion WHERE nombre = 'activo'";
	$result_estado_activo = $conn->query($sql_estado_activo);
	if (!$result_estado_activo || $result_estado_activo->num_rows === 0) {
		if ($isAjax) {
			respondJSON(false, "No se encontró el estado 'activo' en estado_asignacion.");
			} 
		else {
			die("No se encontró el estado 'activo' en estado_asignacion.");
    		}
		}

	$row_estado_activo = $result_estado_activo->fetch_assoc();
	$estado_id_activo = $row_estado_activo['id'];

// Recuperar lista de vehículos
	$vehicles = [];
	$sql_vehiculos = 
		"SELECT id, placa, modelo 
		FROM vehiculos 
		ORDER BY placa ASC";
	$result_vehiculos = $conn->query($sql_vehiculos);
	if ($result_vehiculos) {
		while ($row = $result_vehiculos->fetch_assoc()) {
			$vehicles[] = $row;
			}
		} 
	else {
    	if ($isAjax) {
			respondJSON(false, "Error al obtener vehículos: " . $conn->error);
			} 
		else {
			die("Error al obtener vehículos: " . $conn->error);
    		}
		}

//	Recuperar lista de tracto
	$tractos= [];
	$sql_tractos = "
    SELECT v.id, v.placa,
        CASE 
            WHEN EXISTS (
                SELECT 1 FROM asignaciones_conductor ac
                WHERE ac.vehiculo_tracto_id = v.id AND ac.estado_id = $estado_id_activo
            ) THEN 1
            ELSE 0
        END AS ocupado
    FROM vehiculos v
    JOIN tipo_vehiculo tv ON v.tipo_id = tv.id
    WHERE tv.categoria_id = 1
      AND v.estado_id = (SELECT id FROM estado_vehiculo WHERE nombre = 'activo')
    ORDER BY v.placa ASC
";
	$result_tractos = $conn->query($sql_tractos);
	if ($result_tractos) {
    	while ($row = $result_tractos->fetch_assoc()) {
        	$tractos[] = $row;
    		}
		}

// Obtener remolques
	$remolques = [];
	$sql_remolques = "
    	SELECT v.id, v.placa,
        CASE 
            WHEN v.id IN (
                SELECT vehiculo_remolque_id FROM asignaciones_conductor WHERE estado_id = $estado_id_activo
            	) THEN 1
            ELSE 0
        	END AS ocupado
    	FROM vehiculos v
    	JOIN tipo_vehiculo tv ON v.tipo_id = tv.id
    	WHERE tv.categoria_id = 2
    	ORDER BY v.placa ASC
		";

	// $result_remolques = mysqli_query($conn, $sql_remolques);
	$result_remolques = $conn->query($sql_remolques);
	if ($result_remolques) {
    while ($row = $result_remolques->fetch_assoc()) {
        $remolques[] = $row;
    }
}

// 	Recuperar lista de conductores
	$conductores = [];
	$sql_conductores = "
    SELECT c.id, c.nombres, c.apellidos,
        CASE 
            WHEN EXISTS (
                SELECT 1 FROM asignaciones_conductor ac
                WHERE ac.conductor_id = c.id AND ac.estado_id = $estado_id_activo
            ) THEN 1
            ELSE 0
        END AS ocupado
    FROM conductores c
    WHERE c.activo = 1
    ORDER BY c.nombres ASC
";
	$result_conductores = $conn->query($sql_conductores);
	if ($result_conductores) {
	    while ($row = $result_conductores->fetch_assoc()) {
	        $conductores[] = $row;
	    }
	} else {
    	if ($isAjax) {
    	    respondJSON(false, "Error al obtener conductores: " . $conn->error);
    	} else {
    	    die("Error al obtener conductores: " . $conn->error);
    	}
	}

//DEBUG
	file_put_contents(__DIR__ . '/debug_post.txt', print_r($_POST, true));


// Procesamiento del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se espera recibir vehiculo_id y conductor_id vía POST

	$tracto_id   	= isset($_POST['vehiculo_tracto_id']) ? intval($_POST['vehiculo_tracto_id']) : 0;
	$remolque_id	= isset($_POST['vehiculo_remolque_id']) ? intval($_POST['vehiculo_remolque_id']) : 0;
	$conductor_id 	= isset($_POST['conductor_id']) ? intval($_POST['conductor_id']) : 0;
	$fecha_inicio 	= date('Y-m-d');
	$fecha_fin 		= null;
  
    if ($tracto_id <= 0 || $remolque_id <= 0 || $conductor_id <= 0) {
		$error = "Debes seleccionar tracto, remolque y conductor válidos.";
        if ($isAjax) {
            respondJSON(false, "Debes seleccionar un vehículo y un conductor válidos.");
        	} 
		else {
            $error = "Debes seleccionar un vehículo y un conductor válidos.";
        	}
    	} 
	else {

		// Validación: Verificar si el vehículo ya está asignado con estado activo       
		$sql_check_vehicle = "
            SELECT COUNT(*) AS Total
			FROM asignaciones_conductor
            WHERE (vehiculo_tracto_id = ? OR vehiculo_remolque_id = ?)
            AND estado_id = ?
        ";

		$stmt_check_vehicle = $conn->prepare($sql_check_vehicle);
		if ($stmt_check_vehicle === false) {
    		error_log("❌ Error en prepare (vehículo): " . $conn->error);
    		respondJSON(false, "Error interno al verificar vehículo.");
			}
        $stmt_check_vehicle->bind_param("iii", $tracto_id, $remolque_id, $estado_id_activo);
		$stmt_check_vehicle->execute();

		// $result_check_vehicle = $stmt_check_vehicle->get_result();

		$stmt_check_vehicle->bind_result($total_vehicle);
		$stmt_check_vehicle->fetch();
		$stmt_check_vehicle->close();

        // if ($result_check_vehicle->num_rows > 0)
		if ($total_vehicle > 0) 
			{
            if ($isAjax) {
                respondJSON(false, "El vehículo ya está asignado a otro conductor.");
            } else {
                $error = "El vehículo ya está asignado a otro conductor.";
            }
        }
        
        // Validación: Verificar si el conductor ya tiene una asignación activa
		$sql_check_conductor = "
            SELECT COUNT(*) FROM asignaciones_conductor
            WHERE conductor_id = ? AND estado_id = ?
        	";
		$stmt_check_conductor = $conn->prepare($sql_check_conductor);
        if ($stmt_check_conductor === false) {
            error_log("Error en prepare (conductor): " . $conn->error);
            respondJSON(false, "Error interno al verificar conductor.");
        }
		$stmt_check_conductor->bind_param("ii", $conductor_id, $estado_id_activo);
		$stmt_check_conductor->execute();
		$stmt_check_conductor->bind_result($count_conductor);
		$stmt_check_conductor->fetch();
        $stmt_check_conductor->close();

		if ($count_conductor > 0) {
            respondJSON(false, "El conductor ya tiene una asignación activa.");
        }

		// Capturar auditoría
    	$usuario_id = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : null;
    	$ip_origen  = $_SERVER['REMOTE_ADDR'];

		// Validar que todas las variables estén definidas
		if (!isset($conductor_id, $tracto_id, $remolque_id, $estado_id_activo, $usuario_id)) {
    		respondJSON(false, "Error interno: variables incompletas.");
    		exit();
			}

		 // Insertar nueva asignación compuesta
        $sql_insert = "
            INSERT INTO asignaciones_conductor (
                conductor_id,
                vehiculo_tracto_id,
                vehiculo_remolque_id,
                fecha_inicio,
                fecha_fin,
                estado_id,
				creado_por,
            	ip_origen
            	) 
			VALUES (?, ?, ?, NOW(), NULL, ?, ?, ?)
        	";

        // $result_check_conductor = $stmt_check_conductor->get_result();

        // if ($result_check_conductor->num_rows > 0) {
        //     if ($isAjax) {
        //         respondJSON(false, "El conductor ya tiene asignada una unidad.");
        //     } else {
        //         $error = "El conductor ya tiene asignada una unidad.";
        //     }
        // }
        // $stmt_check_conductor->close();
        
        // Si no hubo errores en validaciones, proceder con la inserción
        if (!isset($error)) {

            // $stmt = $conn->prepare($sql_insert);
            
			$stmt = $conn->prepare($sql_insert);
			if ($stmt === false) {
    			error_log("❌ Error en prepare: " . $conn->error);
    			respondJSON(false, "Error interno al preparar la consulta.");
				}

			
			if ($stmt) {
                // $stmt->bind_param("iiiiiss", $conductor_id, $tracto_id, $remolque_id, $estado_id_activo, $usuario_id, $ip_origen);
                $stmt->bind_param("iiiiss", $conductor_id, $tracto_id, $remolque_id, $estado_id_activo, $usuario_id, $ip_origen);

				// $stmt->bind_param("iiiiiss", $conductor_id, $tracto_id, $remolque_id, $estado_id_activo, $usuario_id, $ip_origen);

				if ($stmt->execute()) {
                    if ($isAjax) {
                        respondJSON(true, "Asignación registrada con éxito.");
                    } else {
                        header("Location: /modulos/asignaciones_conductor/index.php?action=list");
                        exit();
                    }
                } else {
                    if ($isAjax) {
                        respondJSON(false, "Error al insertar la asignación: " . $stmt->error);
                    } else {
                        $error = "Error al insertar la asignación: " . $stmt->error;
                    }
                }
                $stmt->close();
            	} 
			else {
                if ($isAjax) {
                    respondJSON(false, "Error en la preparación de la consulta: " . $conn->error);
                } else {
                    $error = "Error en la preparación de la consulta: " . $conn->error;
                }
            }
        }
    }
   // En el caso no sea AJAX, se sigue mostrando el formulario con mensaje de error
	}
?>

<!DOCTYPE html>
<html lang="es">
	<head>
    	<meta charset="UTF-8">
    	<title>Asignar Vehículo a Conductor</title>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<!-- Bootstrap CSS -->
    	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    	<!-- CSS Personalizado -->
    	<link rel="stylesheet" href="../../css/asignaciones.css">
    	<!-- FontAwesome para íconos -->
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    	<!-- (Opcional) Select2 para mejorar los dropdowns -->
    	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	</head>
	
	<body>
		<div class="container my-5">
    		<div class="d-flex justify-content-between align-items-center mb-4">
   				<h3>Asignar Vehículo a Conductor</h3>
    			<a href="index.php" class="btn btn-secondary">
        			<i class="fas fa-arrow-left"></i> Volver a Asignaciones
    			</a>
			</div>

			<?php if (isset($error)): ?>
        		<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    		<?php endif; ?>

    <!-- El formulario tiene id="asignarForm" para usar AJAX -->
    <form id="asignarForm" action="asignar_conductor.php" method="post" class="needs-validation" novalidate>

		<!-- Tracto -->
	<div class="mb-3">
    	<label for="vehiculo_tracto_id" class="form-label">Tracto</label>
    	<select name="vehiculo_tracto_id" id="vehiculo_tracto_id" class="form-select" required>
    	    <option value="">Seleccione un tracto</option>
        	<?php foreach ($tractos as $tracto): ?>
            	<option value="<?= $tracto['id'] ?>" <?= $tracto['ocupado'] ? 'disabled' : '' ?>>
        			<?= htmlspecialchars($tracto['placa']) ?> <?= $tracto['ocupado'] ? '(Asignado)' : '' ?>
    			</option>
        	<?php endforeach; ?>
    	</select>
    	<div class="invalid-feedback">Por favor, seleccione un tracto.</div>
	</div>

	<!-- Remolque -->
	<div class="mb-3">
    	<label for="vehiculo_remolque_id" class="form-label">Remolque</label>
    	<select name="vehiculo_remolque_id" id="vehiculo_remolque_id" class="form-select" required>
			<option value="">Seleccione un remolque</option>
        	<?php foreach ($remolques as $remolque): ?>
            <option value="<?= $remolque['id'] ?>" <?= $remolque['ocupado'] ? 'disabled' : '' ?>>
        <?= htmlspecialchars($remolque['placa']) ?> <?= $remolque['ocupado'] ? '(Asignado)' : '' ?>
    </option>
        	<?php endforeach; ?>
    	</select>
    	<div class="invalid-feedback">Por favor, seleccione un remolque.</div>
	</div>

    <div class="mb-3">
        <label for="conductor_id" class="form-label">Conductor</label>
        <select name="conductor_id" id="conductor_id" class="form-select" required>
            <option value="">Seleccione un conductor</option>
            <?php foreach ($conductores as $conductor): ?>
            <option value="<?= $conductor['id'] ?>" <?= $conductor['ocupado'] ? 'disabled' : '' ?>>
        <?= htmlspecialchars($conductor['nombres'] . ' ' . $conductor['apellidos']) ?> <?= $conductor['ocupado'] ? '(Asignado)' : '' ?>
    </option>
            <?php endforeach; ?>
        </select>
        <div class="invalid-feedback">
			Por favor, seleccione un conductor.
		</div>
    </div>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Registrar Asignación
    </button>
    </form>
</div>

<!-- jQuery (Para AJAX y Select2) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap JS Bundle (incluye Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- (Opcional) Select2 JS para mejorar los select -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function(){
    // (Opcional) Inicializar Select2 para los dropdowns
    $('#vehiculo_tracto_id, #vehiculo_remolque_id, #conductor_id').select2({
    	width: '100%',
    	placeholder: "Seleccione una opción"
		});

    // Interceptar envío del formulario para enviarlo vía AJAX
    $("#asignarForm").on("submit", function(e){
        e.preventDefault();
        var form = $(this);

        // Si la validación HTML5 falla o Bootstrap la detecta, no hacemos AJAX
        if (this.checkValidity() === false) {
            e.stopPropagation();
            form.addClass("was-validated");
            return;
        }


		// debug
		console.log("Datos enviados:", form.serialize());



        // Enviar el formulario vía AJAX
        $.ajax({
            type: form.attr("method"),
            url: form.attr("action"),
            data: form.serialize(),
            dataType: "json"
        }).done(function(response) {
            if(response.success) {
                // Mostrar notificación (puedes usar un toast de Bootstrap en lugar de alert)
                alert(response.message);
                // Redirigir luego de un breve retardo o actualizar la página
                window.location.href = "index.php";
            } else {
                alert(response.message);
            }
        }).fail(function(){
            alert("Error en la solicitud. Intente nuevamente.");
        });
    });
});
</script>
</body>
</html>
