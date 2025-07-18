<?php
// public_html/modulos/clientes/controllers/ApiController.php

// 0) Mostrar errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1) Configuración global
$config = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/includes/config.php';
if (! file_exists($config)) {
    http_response_code(500);
    die(json_encode(['error' => "Config no encontrada: $config"]));
}
require_once $config;    // define $conn, INCLUDES_PATH, BASE_URL

// 2) Modelos: Cliente y Ubigeo
$modelCliente = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\')
              . '/modulos/clientes/models/Cliente.php';
$modelUbigeo  = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\')
              . '/modulos/clientes/models/Ubigeo.php';

if (! file_exists($modelCliente) || ! file_exists($modelUbigeo)) {
    http_response_code(500);
    die(json_encode(['error' => 'Falta Cliente.php o Ubigeo.php']));
}
require_once $modelCliente;
require_once $modelUbigeo;

Cliente::init($conn);
Ubigeo::init($conn);

// 3) Parámetros
$method = isset($_GET['method']) ? $_GET['method'] : '';
$id     = isset($_GET['id'])     ? (int)$_GET['id']     : 0;

// 4) Dispatch
header('Content-Type: application/json; charset=utf-8');

switch ($method) {

  case 'view':
    // 1) Validar parámetro id
    if (! isset($_GET['id']) || ! ctype_digit($_GET['id'])) {
        http_response_code(400);
        echo '<p class="text-danger">ID de cliente inválido: ' 
             . htmlspecialchars($_GET['id'], ENT_QUOTES) 
             . '</p>';
        exit;
    }
    $id = (int) $_GET['id'];

    // 2) Intentar cargar el cliente
    $cliente = Cliente::findWithUbigeo($id);
    if (! $cliente) {
        http_response_code(404);
        echo '<p class="text-danger">'
           . "Cliente no encontrado (ID={$id})."
           . '</p>';
        exit;
    }

    // 3) Incluir la vista parcial
    header('Content-Type: text/html; charset=utf-8');
    $view = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') 
          . '/modulos/clientes/views/modal.php';
    require $view;
    exit;


  case 'provincias':
    $depId = isset($_GET['departamento_id']) 
           ? (int)$_GET['departamento_id'] 
           : 0;
    $list = Ubigeo::getProvincias($depId);
    echo json_encode($list);
    exit;

  case 'distritos':
    $provId = isset($_GET['provincia_id']) 
            ? (int)$_GET['provincia_id'] 
            : 0;
    $list = Ubigeo::getDistritos($provId);
    echo json_encode($list);
    exit;

  default:
    http_response_code(400);
    echo json_encode(['error' => 'Operación no soportada: ' . $method]);
    exit;
}
