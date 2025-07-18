<?php
	// archivo :    api.php

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	header('Content-Type: application/json');

	
	$method = $_GET['method'] ?? '';
	$base   = __DIR__; // carpeta del módulo


	switch ($method) {
  		case 'vehiculos':
    	require_once $base . '/vehiculosPorTipo.php';
    	break;

  	case 'guardar':
    	require_once $base . '/procesar_asignacion.php';
    	break;

  	case 'listar':
    	require_once $base . '/listar_asignaciones.php';
    	break;

  	case 'finalizar':
    	require_once $base . '/finalizar_asignacion.php';
    	break;

  	default:
    
	json_encode(['error' => 'Método no válido']);


}
