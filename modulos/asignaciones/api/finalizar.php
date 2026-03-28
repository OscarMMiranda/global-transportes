<?php
	// archivo: modulos/asignaciones/api/finalizar.php

	require_once '../model/asignaciones.php';
	$conn = getConnection();

	$id  = intval($_POST['id']);
	$fin = $_POST['fin'] ?? date('Y-m-d H:i:s');

	echo json_encode([
	    'ok' => finalizarAsignacion($conn, $id, $fin)
	]);
