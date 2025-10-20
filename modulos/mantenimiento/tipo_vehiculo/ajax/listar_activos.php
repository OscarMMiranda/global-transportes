<?php
	//	archivo	:	/modulos/mantenimiento/tipo_vehiculo/ajax/listar_activos.php

	// 1) Activar modo depuración solo en entorno de desarrollo
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/../error_log.txt');

	// 2) Cargar dependencias
	require_once __DIR__ . '/../modelo/TipoVehiculoModel.php';
	require_once __DIR__ . '/../controller.php';
	require_once __DIR__ . '/../../../../includes/config.php';

	// 3) Conexión a la base de datos
	$conn = getConnection();

	// 4) Instanciar controlador y renderizar activos
	$ctrl = new TipoVehiculoController($conn);
	$ctrl->renderActivos(); // Este método imprime directamente el HTML de la tabla

	// 5) Cerrar conexión
	$conn->close();
?>