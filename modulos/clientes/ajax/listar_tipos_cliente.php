<?php
	//	archivo: modulos/clientes/ajax/listar_tipos_cliente.php

	header('Content-Type: application/json');
	require_once __DIR__ . '/../../../includes/config.php';

	$conexion = getConnection();

	$sql = "SELECT codigo, nombre FROM tipos_cliente WHERE estado = 'Activo' ORDER BY nombre ASC";
	$result = mysqli_query($conexion, $sql);

	$data = [];

	while ($row = mysqli_fetch_assoc($result)) {
		$data[] = $row;
	}

	echo json_encode($data);
	exit;
