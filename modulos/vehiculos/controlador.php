<?php
	// controlador.php — Lógica principal del módulo Vehículos

	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
	require_once __DIR__ . '/modelo.php';
	require_once __DIR__ . '/includes/funciones.php';

	$conn = getConnection();
	validarSesionAdmin();

	// 1) Detectar acción
	$action = isset($_GET['action']) ? $_GET['action'] : 'list';
	$error  = '';

	// 2) Operaciones que redirigen: store, update, delete, restore
	if ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
		error_log("[STORE] POST recibido: " . print_r($_POST, true));

    	$placa         	= isset($_POST['placa'])      ? trim($_POST['placa'])      	: '';
    	$modelo        	= isset($_POST['modelo'])     ? trim($_POST['modelo'])     	: '';
    	$anio          	= isset($_POST['anio'])       ? intval($_POST['anio'])     	: 0;
    	$tipo_id       	= isset($_POST['tipo_id'])    ? intval($_POST['tipo_id'])  	: 0;
    	$marca_id      	= isset($_POST['marca_id'])   ? intval($_POST['marca_id']) 	: 0;
    	$empresa_id    	= isset($_POST['empresa_id']) ? intval($_POST['empresa_id'])	: 0;
		$estado_id 		= isset($_POST['estado_id']) ? intval($_POST['estado_id']) : 0;
    	$observaciones 	= isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';
    	$usuarioId     	= obtenerUsuarioId();
    	$ipOrigen      	= obtenerIP();	

		if ($placa && $modelo && $anio && $tipo_id && $marca_id && $empresa_id && $estado_id) {
        	$sql = "
          		INSERT INTO vehiculos
  					(placa, modelo, anio, tipo_id, marca_id, empresa_id, estado_id, observaciones,
   					fecha_creado, creado_por, ip_origen)
				VALUES
  					(?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)
        		";
		$stmt = $conn->prepare($sql);
        if (! $stmt) {
            error_log("[STORE] ERROR prepare: " . $conn->error);
            $error = "Error al preparar la consulta.";
        } elseif (! $stmt->bind_param(
                'ssiiiisis',
                $placa,
				$modelo, 
				$anio,
                $tipo_id, 
				$marca_id, 
				$empresa_id,
				$estado_id, 
                $observaciones,
                $usuarioId, 
				$ipOrigen
               	)) {
            error_log("[STORE] ERROR bind_param: " . $stmt->error);
            $error = "Error al vincular los parámetros.";
        } elseif (! $stmt->execute()) {
            error_log("[STORE] ERROR execute: " . $stmt->error);
            $error = "No se pudo guardar el vehículo.";
        } else {
            $nuevoId = $conn->insert_id;
            error_log("[STORE] Vehículo insertado correctamente. ID: " . $nuevoId);
            registrarVehiculoHistorial($conn, $nuevoId, $usuarioId, 'creado');
            header('Location: index.php');
            exit;
        }
    } else {
        $error = "Faltan campos obligatorios.";
    }
}


	
	// 3) Enrutador de vistas (después de haber hecho las redirecciones)
	switch ($action) {
		case 'list':
    		// Listado de vehículos activos e inactivos
    		$vehiculos_activos   = obtenerVehiculos($conn, true);
    		$vehiculos_inactivos = obtenerVehiculos($conn, false);
    		include __DIR__ . '/vistas/listado.php';
    		break;

  		case 'create':
    		// Formulario vacío para crear
    		include __DIR__ . '/vistas/formulario.php';
    		break;

		case 'edit':
    		// Mostrar formulario con datos existentes
    		$id       = isset($_GET['id']) ? intval($_GET['id']) : 0;
    		$vehiculo = getVehiculoPorId($conn, $id);
    		if ($vehiculo) {
    			include __DIR__ . '/vistas/formulario_editar.php';
    			} 
			else {
      			echo "<div class='alert alert-danger'>Vehículo no encontrado. ID: $id</div>";
    			}
    		break;

		case 'view':
  			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
  			$vehiculo = getVehiculoPorId($conn, $id);
  			if ($vehiculo) {
    			include __DIR__ . '/vistas/ver_vehiculo.php'; // crea este archivo si no existe
  				} 
			else {
    			echo "<div class='alert alert-danger'>Vehículo no encontrado. ID: $id</div>";
  				}
  			break;

		case 'store':
  			// Si llegó por POST y no hubo redirect (hubo error), queremos recargar el formulario
  			include __DIR__ . '/vistas/formulario.php';
  			break;

  		case 'update':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // 1) Obtener el ID desde POST (oculto en el formulario)
        $id = isset($_POST['id'])
            ? intval($_POST['id'])
            : (isset($_GET['id']) ? intval($_GET['id']) : 0);

        // 2) Capturar y sanear input
        $placa         = isset($_POST['placa'])       ? trim($_POST['placa'])      : '';
        $modelo        = isset($_POST['modelo'])      ? trim($_POST['modelo'])     : '';
        $anio          = isset($_POST['anio'])        ? intval($_POST['anio'])     : 0;
        $tipo_id       = isset($_POST['tipo_id'])     ? intval($_POST['tipo_id'])  : 0;
        $marca_id      = isset($_POST['marca_id'])    ? intval($_POST['marca_id']) : 0;
        $empresa_id    = isset($_POST['empresa_id'])  ? intval($_POST['empresa_id']): 0;
        $estado_id     = isset($_POST['estado_id'])   ? intval($_POST['estado_id']) : 0;
        $observaciones = isset($_POST['observaciones'])? trim($_POST['observaciones']): '';
        $usuarioId     = obtenerUsuarioId();
        $ipOrigen      = obtenerIP();

        error_log("[UPDATE] Llega POST id={$id} data=" . print_r($_POST, true));

        // 3) Validar que tenemos lo mínimo
        if ($id > 0 && $placa && $modelo && $anio && $tipo_id && $marca_id && $empresa_id) {

            // 4) Llamar a la función de actualización y capturar su resultado
            $ok = actualizarVehiculo(
                $conn,
                $id,
                $placa,
                $modelo,
                $anio,
                $tipo_id,
                $marca_id,
                $empresa_id,
                $estado_id,
                $observaciones,
                $usuarioId,
                $ipOrigen
            );

            if ($ok) {
                error_log("[UPDATE] Éxito al actualizar vehículo ID={$id}");
                registrarVehiculoHistorial($conn, $id, $usuarioId, 'actualizado');
                header('Location: index.php');
                exit;
            } else {
                error_log("[UPDATE] Falló actualizarVehiculo ID={$id}");
                $error = "No se pudo actualizar el vehículo. Revisa el log para detalles.";
            }

        } else {
            $error = "Faltan datos obligatorios o ID inválido.";
        }
    }

    // 5) Si llegamos aquí (GET o fallo), recargamos el objeto para la vista
    $id       = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $vehiculo = getVehiculoPorId($conn, $id);

    include __DIR__ . '/vistas/formulario_editar.php';
    break;

  case 'delete':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id']);
        eliminarVehiculo($conn, $id, $usuarioId, $ipOrigen);
        $_SESSION['msg'] = "Vehículo eliminado correctamente.";
        header("Location: vistas/listado.php");
        exit;
    }
    break;

  case 'restore':
    // Acción de restauración
    require_once __DIR__ . '/acciones/restaurar.php';
    break;

  	default:
    	http_response_code(404);
    	echo "<div class='container mt-5'>
        	<h3 class='text-danger'>Acción no reconocida: <code>{$action}</code></h3>
        	</div>";
    	break;

}