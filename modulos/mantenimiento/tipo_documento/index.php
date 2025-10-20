<?php
	// archivo: /modulos/mantenimiento/tipo_documento/index.php

	session_start();

	// 	1) 	Modo depuración (solo en entorno DEV)
	define('MODO_DEV', true);
	if (MODO_DEV) {
  		error_reporting(E_ALL);
  		ini_set('display_errors', 1);
  		ini_set('log_errors', 1);
  		ini_set('error_log', __DIR__ . '/error_log.txt');
		}
	
	// 	2) 	Cargar configuración
	$pathConfig = __DIR__ . '/../../../includes/config.php';
	if (!file_exists($pathConfig)) {
  		die("❌ No se encontró el archivo de configuración: $pathConfig");
		}
	require_once $pathConfig;

	// 	3) 	Validar sesión y rol
	if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
  		header('Location: ../../../login.php');
  		exit;
		}

	// 	4) 	Registrar acceso al módulo (auditoría básica)
	$usuario = $_SESSION['usuario'];
	$logAcceso = sprintf("[%s] Acceso al módulo tipo_documento por %s\n", date('Y-m-d H:i:s'), $usuario);
	file_put_contents(__DIR__ . '/logs/accesos.log', $logAcceso, FILE_APPEND);

	// 5) Cargar vista principal
	$vista = __DIR__ . '/vistas/listar.php';
	if (!file_exists($vista)) {
  		die("❌ No se encontró la vista principal: $vista");
		}
	require_once $vista;

	