<?php
	//	archivo: /modulos/asignaciones/api/guardar.php

	require_once '../model/asignaciones.php';
	$conn = getConnection();

	$data = [
	    'conductor_id' => intval($_POST['conductor_id']),
	    'tracto_id'    => intval($_POST['tracto_id']),
	    'carreta_id'   => intval($_POST['carreta_id']),
	    'inicio'       => $_POST['inicio']
	];

	echo json_encode([
	    'ok' => guardarAsignacion($conn, $data)
	]);
