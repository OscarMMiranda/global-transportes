<?php
	//	archivo: modulos/asistencias/ajax/get_tipos.php

	require_once __DIR__ . '/../../../includes/config.php';
	$conn = getConnection();

	$sql = "SELECT codigo, descripcion FROM asistencia_tipos ORDER BY descripcion ASC";
	$res = $conn->query($sql);

	$lista = [];

	while ($row = $res->fetch_assoc()) {
		$lista[] = $row;
		}

	header('Content-Type: application/json');
	echo json_encode($lista);
