<?php
	// archivo: /modulos/empleados/acciones/listar_empresas_simple.php

	header('Content-Type: application/json');

	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

	$conn = getConnection();

	$sql = "SELECT id, razon_social FROM empresa ORDER BY razon_social ASC";
	$res = $conn->query($sql);

	$data = [];

	while ($row = $res->fetch_assoc()) {
		$data[] = $row;
	}

	echo json_encode([
    	"success" => true,
		"data" => $data
	]);
