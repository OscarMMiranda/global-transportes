<?php
	// public_html/modules/clientes/index.php
	
	require_once __DIR__ . '/../../includes/config.php';

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
