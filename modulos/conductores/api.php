<?php
// modulos/conductores/api.php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	header('Content-Type: application/json; charset=utf-8');

	session_start();
	if (! isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    	http_response_code(403);
    	echo json_encode(array('success' => false, 'error' => 'No autorizado'));
    	exit;
		}

	// conexión
	require_once __DIR__ . '/../../includes/conexion.php';

	// controlador
	require_once __DIR__ . '/controllers/conductorescontrollers.php';

	$ctrl = new ConductoresController($conn);

	// helper
		function respond($payload, $code = 200)
			{
    		http_response_code($code);
    		echo json_encode($payload);
    		exit;
			}

	// sin usar PHP7 “null coalescing”
		$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

	switch ($op) {
 	   case 'list':
    		// 1) Decide si mostramos sólo activos o todos (activos + inactivos)
    		$filter = isset($_GET['filter']) && $_GET['filter'] === 'all' ? 'all' : 'active';

    		// 2) Arma el WHERE según el filtro
    		$where = $filter === 'all'
    		    ? ''               // sin filtro → trae todos
    		    : 'WHERE activo=1'; // sólo activos

    		// 3) Ejecuta la consulta
    		$sql = "SELECT id, 
					nombres, 
					apellidos, 
					dni, 
					licencia_conducir, 
					telefono, 
					correo,
					direccion,
					foto,
					activo
    	        FROM conductores
    	        $where
    	        ORDER BY apellidos, nombres";
    		$result = $conn->query($sql);
    		$rows = $result->fetch_all(MYSQLI_ASSOC);

    		// 4) Devuelve el JSON
    		respond(['success' => true, 'data' => $rows]);
    		break;

		case 'get':
    		$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    		if (! $id) {
    			respond(array('success' => false, 'error' => 'ID inválido'), 400);
    			}
    		$res = $ctrl->get($id);
    		respond($res, $res['success'] ? 200 : 404);
    		break;

	    case 'save':
    	    $data = array(
        	    'id'                => filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ? : 0,
            	'nombres'           => isset($_POST['nombres'])   ? trim($_POST['nombres'])   : '',
        	    'apellidos'         => isset($_POST['apellidos']) ? trim($_POST['apellidos']) : '',
        	    'dni'               => isset($_POST['dni'])       ? trim($_POST['dni'])       : '',
        	    'licencia_conducir' => isset($_POST['licencia_conducir']) ? trim($_POST['licencia_conducir']) : '',
        	    'telefono'          => isset($_POST['telefono'])  ? trim($_POST['telefono'])  : '',
        	    'correo'            => isset($_POST['correo'])    ? trim($_POST['correo'])    : '',
				'direccion'			=> isset($_POST['direccion']) ? trim($_POST['direccion']) : "",
				
				'activo'            => isset($_POST['activo'])    ? 1 : 0
        	);
        	if ($data['nombres']=='' || $data['apellidos']=='' || ! preg_match('/^\d{8}$/', $data['dni'])) {
            	respond(array('success'=>false,'error'=>'Datos inválidos'),422);
        	}

			// Procesar imagen
			$fotoName = '';

			// Verifica si se subió una imagen
			if (!empty($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    			$tmp  = $_FILES['foto']['tmp_name'];
    			$ext  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    			$fotoName = uniqid('cond_') . '.' . $ext;

    			$destino = __DIR__ . '/../../uploads/conductores/' . $fotoName;

    			if (!move_uploaded_file($tmp, $destino)) {
        			respond(['success' => false, 'error' => 'No se pudo guardar la imagen']);
    				}
				}
			
			$data['foto'] = $fotoName;

        	$res = $ctrl->save($data);
        	respond($res, $res['success'] ? 200 : 500);
        	break;

    	case 'delete':
        	$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        	if (! $id) {
        	    respond(array('success' => false, 'error' => 'ID inválido'), 400);
        		}
        	$res = $ctrl->delete($id);
        	respond($res, $res['success'] ? 200 : 500);
        	break;

		case 'restore':
    		$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    		if (!$id) {
        		respond(['success' => false, 'error' => 'ID inválido']);
    			}

    		$stmt = $conn->prepare("UPDATE conductores SET activo = 1 WHERE id = ?");
    		$stmt->bind_param('i', $id);
    		$stmt->execute();

    		if ($stmt->affected_rows > 0) {
        		respond(['success' => true]);
    			} 
			else {
        		respond(['success' => false, 'error' => 'No se pudo restaurar']);
    			}
    		break;

    		default:
        	respond(array('success' => false, 'error' => 'Operación no válida'), 400);
	}
