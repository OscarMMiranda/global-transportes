<?php
	// archivo: /modulos/seguridad/usuarios/acciones/eliminar_usuario.php

	require_once __DIR__ . '/../../../../includes/config.php';
	require_once INCLUDES_PATH . '/permisos.php';
	requirePermiso("usuarios", "eliminar");

	header('Content-Type: application/json; charset=utf-8');

	$id = intval($_POST['id'] ?? 0);

	if ($id <= 0) {
    	echo json_encode(["ok" => false, "msg" => "ID inválido"]);
    	exit;
		}	

	$db = $GLOBALS['db'];

	// Verificar que el usuario exista
	$sql = "SELECT id FROM usuarios WHERE id = ?";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->store_result();

	if ($stmt->num_rows === 0) {
    	echo json_encode(["ok" => false, "msg" => "El usuario no existe"]);
		exit;
		}

	// Eliminar
	$sql = "DELETE FROM usuarios WHERE id = ?";
	$stmt = $db->prepare($sql);
	$stmt->bind_param("i", $id);

	if ($stmt->execute()) {
		echo json_encode(["ok" => true, "msg" => "Usuario eliminado correctamente"]);
		} 
	else {
    	echo json_encode(["ok" => false, "msg" => "No se pudo eliminar el usuario"]);
		}