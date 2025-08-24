<?php
	// archivo	:	/modulos/clientes/controllers/ListController.php

	// ─── 0) Mostrar todos los errores (solo en desarrollo) ────────────────────
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	
	// ─── 1) Configuración global ───────────────────────────────────────────────
	// Usa DOCUMENT_ROOT para no depender de ../../..
	$config = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/includes/config.php';
	if (! file_exists($config)) {
    	die("Config no existe en: $config");
		}
	
		require_once $config;  // deja disponibles $conn, INCLUDES_PATH, BASE_URL

	// ─── 2) Cargar modelo Cliente ─────────────────────────────────────────────
	$model = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') 
    	. '/modulos/clientes/models/Cliente.php';
		if (! file_exists($model)) {
    		die("Modelo Cliente no existe en: $model");
			}
		require_once $model;

	// ─── 3) Inicializar conexión en el modelo ────────────────────────────────
	Cliente::init($conn);

	// ─── 4) Recuperar datos ───────────────────────────────────────────────────
	try {
    	$clientes = Cliente::all();
		} 
	catch (Exception $e) {
    	error_log("ListController error: " . $e->getMessage());
    	$clientes = [];
    	$errorMsg = "No se pudieron cargar los clientes.";
		}

	// ─── 4.1) Capturar mensaje de operación (opcional) ─────────────────────────
	$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

	

	// ─── 5) Definir assets de este módulo ─────────────────────────────────────
	define('MODULE_CSS', 'clientes.css');
	define('MODULE_JS',  'clientes.js');

	// ─── 6) Cargar header global ───────────────────────────────────────────────
	$header = INCLUDES_PATH . '/header_erp.php';
	if (! file_exists($header)) {
    	die("Header no existe en: $header");
		}
	require_once $header;

	// ─── 7) Mostrar error si lo hay ───────────────────────────────────────────
	if (! empty($errorMsg)) {
    	echo '<div class="container mt-3">'
       			. 	'<div class="alert alert-danger">'
       				.    htmlspecialchars($errorMsg, ENT_QUOTES)
       			.  '</div>'
       		. '</div>';
		}

	// ─── 8) Incluir la vista de listado ───────────────────────────────────────
	$view = __DIR__ . '/../views/list.php';
	if (! file_exists($view)) {
    	die("Vista list.php no existe en: $view");
		}
	require $view;

	// ─── 9) Cargar footer global ───────────────────────────────────────────────
	$footer = INCLUDES_PATH . '/footer.php';
	if (! file_exists($footer)) {
    	die("Footer no existe en: $footer");
		}
	require_once $footer;
