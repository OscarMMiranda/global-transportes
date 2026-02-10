<?php
	// archivo: /modulos/asistencias/vistas/modificar_asistencia.php

	// Configuración de errores
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/error_log.txt');

	// Sesión y conexión
	require_once __DIR__ . '/../../../includes/config.php';
	$conn = getConnection();

	// CORE
	require_once __DIR__ . '/../core/conductores.func.php';
	require_once __DIR__ . '/../core/empresas.func.php';
	require_once __DIR__ . '/../core/asistencia.func.php';

	// Listas base
	$conductores = obtener_conductores($conn);
	$empresas = obtener_empresas($conn);
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		
    	<meta charset="utf-8">
    	<title>Modificar Asistencia</title>

		<!--	Bootstrap y FontAwesome 	-->
    	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

		 <!-- 	CSS específico del módulo 	-->
		<link rel="stylesheet" href="/modulos/asistencias/css/modificar_asistencia.css">

	</head>

	<body>
		<!--	CONTENIDO PRINCIPAL 	-->
		<div class="container py-4">
    		<?php include __DIR__ . '/partes/header_modificar.php'; ?>
    		<?php include __DIR__ . '/partes/filtros.php'; ?>
    		<?php include __DIR__ . '/partes/tabla_resultados.php'; ?>
		</div>

		<!--	MODAL DE EDICIÓN 	-->	
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/modulos/asistencias/modales/modal_modificar_asistencia.php'; ?>

		<!--	SCRIPTS 	-->
		<?php include __DIR__ . '/partes/scripts_modificar.php'; ?>
	</body>

</html>
