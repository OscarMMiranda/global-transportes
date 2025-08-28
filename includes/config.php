<?php
	// includes/config.php

	// 1. Errores en pantalla (solo DEV)
	ini_set('display_errors',         '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

	// 2. Sesión
	if (session_status() === PHP_SESSION_NONE) {
	    session_start();
		}

	// 3. Rutas
	define('BASE_PATH',    realpath(dirname(__FILE__) . '/..'));
	define('INCLUDES_PATH', BASE_PATH . '/includes');
	define('PUBLIC_PATH',   BASE_PATH . '/public_html');
	define('BASE_URL',      '/');

	// 4. Zona horaria
	date_default_timezone_set('America/Lima');

	// 5. Cargar la conexión
	require_once INCLUDES_PATH . '/conexion.php';

	// 6. Función de redirección (opcional)
	function redirectToLogin()
		{
		header("Location: http://www.globaltransportes.com/login.php");
    	exit;
		}
