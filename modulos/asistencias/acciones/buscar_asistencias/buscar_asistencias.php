<?php
	// archivo: /modulos/asistencias/acciones/buscar_asistencias/buscar_asistencias.php

	header('Content-Type: application/json');

	require_once __DIR__ . '/../../../../includes/config.php';
	$conn = getConnection();

	require_once __DIR__ . '/validar_filtros.php';
	require_once __DIR__ . '/resolver_periodo.php';
	require_once __DIR__ . '/query_builder.php';
	require_once __DIR__ . '/ejecutar_busqueda.php';

	$conductor = intval($_POST['conductor'] ?? 0);
	$periodo   = $_POST['periodo'] ?? '';
	$desde     = $_POST['desde'] ?? '';
	$hasta     = $_POST['hasta'] ?? '';
	$tipo      = $_POST['tipo'] ?? '';

	$valid = validar_filtros($conductor, $periodo, $desde, $hasta);

	if (!$valid['ok']) {
    	echo json_encode($valid);
    	exit;
	}

	list($f_desde, $f_hasta) = resolver_periodo($periodo, $desde, $hasta);

	list($sql, $types) = construir_query($tipo);

	$data = ejecutar_busqueda($conn, $sql, $types, $conductor, $f_desde, $f_hasta, $tipo);

	echo json_encode(['ok' => true, 'data' => $data]);
