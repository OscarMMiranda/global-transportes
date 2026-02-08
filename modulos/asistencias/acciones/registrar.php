<?php
// archivo: /modulos/asistencias/acciones/registrar.php

require __DIR__ . '/../../../includes/config.php';

// CORE
require __DIR__ . '/../core/asistencia.func.php';
require __DIR__ . '/../core/empresas.func.php';
require __DIR__ . '/../core/conductores.func.php';
require __DIR__ . '/../core/fechas.func.php';
require __DIR__ . '/../core/matriz.func.php';
require __DIR__ . '/../core/helpers.func.php';

header('Content-Type: application/json');

$conn = getConnection();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Tipo de operación
$tipo_registro = isset($_POST['tipo_registro']) ? $_POST['tipo_registro'] : '';

if ($tipo_registro == '') {
    echo json_encode(array('ok' => false, 'error' => 'Tipo de registro no especificado'));
    exit;
}

// LOG FORENSE
file_put_contents(__DIR__ . '/debug_registrar.txt', print_r($_POST, true), FILE_APPEND);

/* ============================================================
   SWITCH PRINCIPAL (UN SOLO ENDPOINT)
   ============================================================ */

switch ($tipo_registro) {

    case 'asistencia':
        $r = registrar_asistencia($conn, $_POST);
        echo json_encode($r);
        break;

    case 'dia_no_laborable':
        $r = registrar_dia_no_laborable($conn, $_POST);
        echo json_encode($r);
        break;

    case 'permiso':
        $r = registrar_permiso($conn, $_POST);
        echo json_encode($r);
        break;

    case 'vacacion':
        $r = registrar_vacacion($conn, $_POST);
        echo json_encode($r);
        break;

    default:
        echo json_encode(array('ok' => false, 'error' => 'Tipo de registro inválido'));
        break;
}
