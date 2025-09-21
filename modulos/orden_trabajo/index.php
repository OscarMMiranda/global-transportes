<?php
// archivo: /modulos/orden_trabajo/index.php


// 2) Modo depuración (solo DEV)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors',     1);
    ini_set('error_log',      __DIR__ . '/error_log.txt');

    // 3) Cargar config.php (define getConnection() y rutas)
    require_once __DIR__ . '/../../includes/config.php';

    // 4) Obtener la conexión
    $conn = getConnection();

	

	require_once '../../includes/header_erp.php';

	// Redirige al controlador que prepara el listado
	require_once __DIR__ . '/controllers/ListController.php';

	cargarListado($conn);


	require_once '../../includes/footer_erp.php';


    