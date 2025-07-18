<?php
	error_reporting(E_ALL); ini_set('display_errors',1);
	require_once $_SERVER['DOCUMENT_ROOT'].'/includes/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/modulos/clientes/models/Cliente.php';
	Cliente::init($conn);

	$clientesEliminados = Cliente::allDeleted();

	define('MODULE_CSS','clientes.css');
	define('MODULE_JS','clientes.js');

	require_once INCLUDES_PATH.'/header_erp.php';
	require __DIR__ . '/../views/trash.php';
	require_once INCLUDES_PATH.'/footer.php';
