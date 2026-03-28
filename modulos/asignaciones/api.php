<?php
// archivo: /modulos/asignaciones/api.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/model.php';

$conn   = getConnection();
$method = isset($_GET['method']) ? $_GET['method'] : '';

header('Content-Type: application/json; charset=utf-8');

switch ($method) {
	// ============================================================
// OBTENER ASIGNACIÓN POR ID
// ============================================================
case 'obtener':

    if (!isset($_GET['id'])) {
        echo json_encode(['ok' => false, 'error' => 'ID no proporcionado']);
        exit;
    }

    $id = intval($_GET['id']);
    $data = obtenerAsignacionPorId($conn, $id);

    if ($data) {
        echo json_encode($data);
    } else {
        echo json_encode(['ok' => false, 'error' => 'No encontrado']);
    }
    break;

	




    // ============================================================
    // LISTAR ASIGNACIONES
    // ============================================================
    case 'listar':
        $data = obtenerAsignaciones($conn);
        echo json_encode($data);
        break;

    // ============================================================
    // LISTAR CONDUCTORES ACTIVOS
    // ============================================================
    case 'conductores':
    	echo json_encode(obtenerConductoresTodos($conn));
    break;

    case 'conductores_disponibles':
    	echo json_encode(obtenerConductoresDisponibles($conn));
    break;

    // ============================================================
    // LISTAR TRACTOS y carretas
    // ============================================================
    case 'tractos':
    	echo json_encode(obtenerTractosTodos($conn));
	break;

	case 'tractos_disponibles':
    	echo json_encode(obtenerTractosDisponibles($conn));
	break;

	case 'carretas':
    	echo json_encode(obtenerCarretasTodos($conn));
	break;

	case 'carretas_disponibles':
    	echo json_encode(obtenerCarretasDisponibles($conn));
	break;

    // ============================================================
    // GUARDAR ASIGNACIÓN
    // ============================================================
    case 'guardar':

        if (!isset($_POST['conductor_id'], $_POST['tracto_id'], $_POST['carreta_id'], $_POST['inicio'])) {
            echo json_encode(['ok' => false, 'error' => 'Datos incompletos']);
            exit;
        }

        $data = [
            'conductor_id' => (int)$_POST['conductor_id'],
            'tracto_id'    => (int)$_POST['tracto_id'],
            'carreta_id'   => (int)$_POST['carreta_id'],
            'inicio'       => $_POST['inicio']
        ];

        $ok = guardarAsignacion($conn, $data);

        echo json_encode([
            'ok'    => $ok,
            'error' => $ok ? null : mysqli_error($conn)
        ]);
        break;

    // ============================================================
    // FINALIZAR ASIGNACIÓN
    // ============================================================
    case 'finalizar':

        $id  = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $fin = isset($_POST['fin']) ? $_POST['fin'] : date('Y-m-d H:i:s');

        if ($id <= 0) {
            echo json_encode(['ok' => false, 'error' => 'ID inválido']);
            exit;
        }

        $ok = finalizarAsignacion($conn, $id, $fin);

        echo json_encode([
            'ok'    => $ok,
            'error' => $ok ? null : mysqli_error($conn)
        ]);
        break;

    // ============================================================
    // MÉTODO NO SOPORTADO
    // ============================================================
    default:
        echo json_encode(['ok' => false, 'error' => 'Método no soportado']);
        break;
}
