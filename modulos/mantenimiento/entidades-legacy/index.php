<?php
	// archivo : /modulos/mantenimiento/entidades/index.php

	// 1) Modo depuración (solo DEV)
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/error_log.txt');

	// 2) Cargar config.php (define getConnection() y rutas)
	require_once __DIR__ . '/../../../includes/config.php';

	// 3) Obtener la conexión
	$conn = getConnection();
	if (!($conn instanceof mysqli)) {
    	error_log("❌ No se pudo establecer conexión desde index.php");
    	die("Error de conexión con la base de datos.");
		}

	// 4) Determinar acción
	$action = isset($_GET['action']) ? $_GET['action'] : 'list';
	
	// 5) Enrutamiento modular
	switch ($action) {
    	case 'list':
        	require_once __DIR__ . '/controllers/ListController.php';
        	break;
    	case 'form':
        	require_once __DIR__ . '/controllers/FormController.php';
        	break;
    	case 'delete':
			require_once __DIR__ . '/controllers/DeleteController.php';
            break;
    	case 'restore':
    	    require_once __DIR__ . '/controllers/RestoreController.php';
    	    break;
    	case 'trash':
    	    require_once __DIR__ . '/controllers/TrashController.php';
    	    break;
    	case 'api':
    	    require_once __DIR__ . '/controllers/ApiController.php';
    	    break;
    	default:
        	header("HTTP/1.0 404 Not Found");
    	    echo "Acción no válida.";
    	    break;
	}