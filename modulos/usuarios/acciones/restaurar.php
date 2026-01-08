<?php
	// archivo: /modulos/usuarios/acciones/restaurar.php
	// ----------------------------------------------
	// Restaura un usuario eliminado (activo)
	// ----------------------------------------------

	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/auditoria.php';

	require_once __DIR__ . '/../controllers/usuarios_controller.php';

	$conn = getConnection();
	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

	if ($id > 0) {
    	cambiarEstadoUsuario($conn, $id, 0);
		}

	header("Location: ../index.php");
	exit;