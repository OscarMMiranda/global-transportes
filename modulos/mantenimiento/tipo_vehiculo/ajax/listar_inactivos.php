<?php
	//	archivo	: 	/modulos/mantenimiento/tipo_vehiculo/ajax/listar_inactivos.php

	// 1) Activar modo depuración solo en entorno de desarrollo
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/../error_log.txt');

	require_once __DIR__ . '/../modelo/TipoVehiculoModel.php';
	require_once __DIR__ . '/../controller.php';
	require_once __DIR__ . '/../../../../includes/config.php';

	$conn = getConnection();
	$ctrl = new TipoVehiculoController($conn);
	$ctrl->renderInactivos(); 		// este método debe imprimir HTML directamente
	$conn->close();

?>