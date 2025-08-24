<?php
	// archivo	:	/modulos/clientes/index.php
	

// 2) Modo depuración (solo DEV)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors',     1);
    ini_set('error_log',      __DIR__ . '/error_log.txt');

    // 3) Cargar config.php (define getConnection() y rutas)
    require_once __DIR__ . '/../../includes/config.php';

    // 4) Obtener la conexión
    $conn = getConnection();

	$action = isset($_GET['action']) ? $_GET['action'] : 'list';

	switch ($action) {
		case 'list':
    		require __DIR__ . '/controllers/ListController.php';
    		break;
  		case 'form':
    		require __DIR__ . '/controllers/FormController.php';
    		break;
  		case 'delete':
    		require __DIR__ . '/controllers/DeleteController.php';
    		break;
		case 'trash':   
			require 'controllers/TrashController.php';    
			break;
  		case 'restore': 
			require 'controllers/RestoreController.php';  
			break;
  		case 'api':
    		require __DIR__ . '/controllers/ApiController.php';
    		break;

  		default:
    		header("HTTP/1.0 404 Not Found");
    		echo "Acción no válida.";
    		break;
		}
