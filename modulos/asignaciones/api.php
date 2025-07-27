<?php
// modulos/asignaciones/api.php

// Mostrar errores en desarrollo (PHP 5.6)
ini_set('display_errors',    1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/conexion.php';
require_once __DIR__ . '/../../includes/helpers.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (! isset($_SESSION['usuario'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No autorizado']);
    exit;
}


// Helpers de JSON
function jsonResponse($data, $status)
	{	
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
	}

function jsonError($msg, $status) {
    jsonResponse(['error' => $msg], $status);
}

// Reutilizable para SELECT
function fetchAllRows($conn, $sql, $errMsg) {
    $res = $conn->query($sql);
    if (! $res) {
        jsonError("$errMsg: " . $conn->error, 500);
    }
    $rows = array();
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}





//  header('Content-Type: application/json');
// header('Content-Type: text/plain');
// var_dump($_GET);
// exit;


// Determinar método (por defecto 'listar')

$method = isset($_GET['method']) && $_GET['method'] !== ''
    ? $_GET['method']
    : 'listar';



switch ($method) {
	case 'listar':
        $sql = "
            SELECT
                a.id,
                CONCAT(u.nombres, ' ', u.apellidos) AS conductor,
                t.placa       AS tracto,
                c.placa       AS carreta,
                a.fecha_inicio AS inicio,
                a.fecha_fin    AS fin,
                a.estado
            FROM asignaciones AS a
            LEFT JOIN conductores AS u
                ON a.id_conductor = u.id
            LEFT JOIN vehiculos AS t
                ON a.id_vehiculo_tracto = t.id
            LEFT JOIN vehiculos AS c
                ON a.id_vehiculo_carreta = c.id
            ORDER BY a.fecha_inicio DESC
        ";
		 $data = fetchAllRows($conn, $sql, 'Error en consulta listar');
        registrarActividad($conn, $_SESSION['usuario'], 'API: listar asignaciones');
        jsonResponse($data, 200);


	case 'conductores':
        $sql = "SELECT id, CONCAT(nombres, ' ', apellidos) AS nombre
                FROM conductores
                WHERE activo = 1
                ORDER BY nombres ASC";
        $data = fetchAllRows($conn, $sql, 'Error en consulta conductores');
        registrarActividad($conn, $_SESSION['usuario'], 'API: listar conductores');
        jsonResponse($data, 200);


	//	*** TRACTOS ***
	
	case 'tractos':
    $sql = "
        SELECT v.id, v.placa
        FROM vehiculos v
        LEFT JOIN tipo_vehiculo tv ON v.tipo_id = tv.id
        LEFT JOIN categoria_vehiculo cv ON tv.categoria_id = cv.id
        WHERE cv.nombre = 'tracto'
          AND v.activo = 1
        ORDER BY v.placa ASC
    ";
    $data = fetchAllRows($conn, $sql, 'Error en consulta tractos');
    registrarActividad($conn, $_SESSION['usuario'], 'API: listar tractos');
    jsonResponse($data, 200);
    break;


	//	*** VEHICULOS ***

	case 'carretas':
    $sql = "
        SELECT v.id, v.placa
        FROM vehiculos v
        LEFT JOIN tipo_vehiculo tv ON v.tipo_id = tv.id
        LEFT JOIN categoria_vehiculo cv ON tv.categoria_id = cv.id
        WHERE cv.nombre = 'carreta'
          AND v.activo = 1
        ORDER BY v.placa ASC
    ";
    $data = fetchAllRows($conn, $sql, 'Error en consulta carretas');
    registrarActividad($conn, $_SESSION['usuario'], 'API: listar carretas');
    jsonResponse($data, 200);
    break;




	case 'guardar':
        // Solo POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        	http_response_code(405);
        	echo json_encode(['error' => 'Método HTTP no permitido']);
        	exit;
    		}

        $id_conductor  = intval($_POST['conductor_id']);
    	$id_tracto     = intval($_POST['tracto_id']);
    	$id_carreta    = intval($_POST['carreta_id']);
    	$fecha_inicio  = $conn->real_escape_string($_POST['fecha_inicio']);
    	$observaciones = $conn->real_escape_string($_POST['observaciones']);

		$sql = "
        	INSERT INTO asignaciones (
            	id_conductor,
            	id_vehiculo_tracto,
            	id_vehiculo_carreta,
            	fecha_inicio,
            	observaciones,
            	estado
        		)	 
			VALUES (
            	$id_conductor,
            	$id_tracto,
            	$id_carreta,
            	'$fecha_inicio',
            	'$observaciones',
            	1
        		)
    		";

		$res = $conn->query($sql);
    if (! $res) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al guardar: ' . $conn->error]);
        exit;
    }

    $newId = $conn->insert_id;

    // 📝 Registro de actividad detallado


	registrarActividad(
        $conn,
        $_SESSION['usuario'],
        "Asignó tracto ID $id_tracto con carreta ID $id_carreta al conductor ID $id_conductor (Asignación #$newId)"
    );

    echo json_encode(['success' => true, 'id' => $newId]);
    exit;
		





   default:
        header('Allow: GET, POST', true, 405);
        jsonError("Método '{$method}' no válido", 405);


}

