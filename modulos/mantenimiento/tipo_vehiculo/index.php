<?php
	//	archivo	:	/modulos/mantenimiento/tipo_vehiculo/index.php

	// 1) Mostrar errores (solo en desarrollo)
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	// 2) Sesión y control de acceso
	session_start();
	if (empty($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
	    header('Location: ../../../login.php');
	    exit;
		}

	// 3) Carga de dependencias globales
	require_once __DIR__ . '/../../../includes/conexion.php';
	require_once __DIR__ . '/../../../includes/config.php';
	require_once __DIR__ . '/modelo/TipoVehiculoModel.php';
	require_once __DIR__ . '/controller.php';

	if (session_status() === PHP_SESSION_NONE) {
    	session_start();
		}

	// 4) Conexión única
	$conn = getConnection();
	if ($conn->connect_error) {
	    die("Error de conexión: " . $conn->connect_error);
		}

	// 5) Instancia del controlador
	$ctrl = new TipoVehiculoController($conn);

	// 6) Mini­ruteo por acción
	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'index';

	switch ($action) {
	    case 'create':
	        $ctrl->create();    // muestra formulario
	        break;

	    case 'store':
	        $ctrl->store($_POST);
	        break;

	    case 'edit':
	        $ctrl->edit((int) $_GET['id']);
	        break;

	    case 'update':
	        $ctrl->update($_POST);
	        break;

	    case 'delete':
	        $ctrl->delete((int) $_GET['id']);
	        break;
		
		case 'reactivar_prompt':
        	$ctrl->reactivar_prompt();
        	break;

    	case 'reactivar':
        	$ctrl->reactivar();
        	break;

	    default:
	        $ctrl->index();     // listado y render view.php
	        break;
	}

	// 7) Cerrar conexión
	$conn->close();
