<?php
	//	archivo: /modulos/asignaciones/api/obtener.php

	require_once '../model/asignaciones.php';
	$conn = getConnection();

	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

	echo json_encode(obtenerAsignacionPorId($conn, $id));
