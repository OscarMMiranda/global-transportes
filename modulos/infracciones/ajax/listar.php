<?php
// archivo: /modulos/infracciones/ajax/listar.php

ob_clean();
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/InfraccionesController.php';
require_once __DIR__ . '/../../../includes/config.php';

$controller = new InfraccionesController($GLOBALS['db']);

// Obtener lista original (ya con monto_base correcto desde BD)
$lista = $controller->listar();

// Formatear monto_base para DataTables
foreach ($lista as &$inf) {
    $inf['monto_base'] = number_format(floatval($inf['monto_base']), 2, '.', '');
}

// DataTables requiere: { "data": [...] }
echo json_encode(array("data" => $lista));
exit;
