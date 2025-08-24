<?php
	
	// /modulos/mantenimiento/agencia_aduana/editar_agencia_aduana.php

		session_start();

		// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../../includes/config.php';
// require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();




	// 2) Verificar conexión y permisos
		if (!$conn) {
	    	die("Error en la conexión: " . mysqli_connect_error());
			}
		if (empty($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
	    	header('Location: ../sistema/login.php');
	    	exit;
			}

	// cargar controller
		require_once __DIR__.'/controllers/agencias_controller.php';

	// procesar formulario
		$error = '';
		$registro = [];
		if ($_SERVER['REQUEST_METHOD']==='POST') {
			$error = procesarFormulario($_POST);
			}

	// eliminar
	if (isset($_GET['eliminar'])) {
		eliminarAgencia((int)$_GET['eliminar']);
		header('Location: editar_agencia_aduana.php?msg=eliminado'); exit;
		}

	// reactivar
	if (isset($_GET['reactivar'])) {
		reactivarAgencia((int)$_GET['reactivar']);
		header('Location: editar_agencia_aduana.php?msg=reactivado'); exit;
		}

	// cargar registro o plantilla vacía
		$registro = isset($_GET['editar'])
    	? obtenerRegistro((int)$_GET['editar'])
    	: agenciaVacia();

	// cargar datos auxiliares
		$departamentos = listarDepartamentos();
		$provincias    = listarProvincias();
		$distritos     = listarDistritos();
		$agencias      = listarAgencias();

	// renderizar vistas
		include __DIR__.'/views/layout/header.php';
		include __DIR__.'/views/agencias/form.php';
		include __DIR__.'/views/agencias/list.php';
		include __DIR__.'/views/layout/footer.php';


