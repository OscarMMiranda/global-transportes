<?php
	// archivo: /modulos/mantenimiento/valores_referenciales/index.php

	session_start();

	// 🛠️ Modo depuración (solo DEV)
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/error_log.txt');

	// 🔗 Conexión y controlador
	require_once __DIR__ . '/../../../includes/config.php';
	$conn = getConnection();
	require_once __DIR__ . '/controllers/valores_controller.php';

	// 🔐 Validación de sesión y rol
	if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
  		header('Location: ../../../login.php');
  		exit;
		}

	// ⚙️ Acciones
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  		include __DIR__ . '/acciones/guardar.php';
  		exit;
		}

	// 📦 Datos para vista
	require_once __DIR__ . '/helpers/datos_vista.php';
?>

<!DOCTYPE html>
<html lang="es">
	<head>
  		<meta charset="UTF-8">
  		<title>Valores Referenciales – Mantenimiento</title>
  		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body class="bg-light">

  		<div class="container py-4">
    		<a href="../mantenimiento.php" class="btn btn-outline-secondary btn-sm mb-3">
      			<i class="fas fa-arrow-left me-1"></i> Volver a Mantenimiento
    		</a>

    		<h1 class="h4 mb-4">💰 Valores Referenciales</h1>

    		<?php include __DIR__ . '/componentes/mensajes_flash.php'; ?>

    			<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalValores">
      				+ Nuevo valor
    			</button>

    		<?php include __DIR__ . '/componentes/tabla_valores.php'; ?>
  		</div>

  		<?php include __DIR__ . '/modales/modal_valores.php'; ?>

  		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	</body>
</html>