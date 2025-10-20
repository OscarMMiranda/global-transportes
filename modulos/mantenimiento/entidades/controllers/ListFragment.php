<?php
    //  archivo :   /modulos/mantenimiento/entidades/controllers/ListFragment.php

	require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
	$conn = getConnection();
	include_once dirname(__FILE__) . '/../helpers/EntidadesHelpers.php';

	$estado = isset($_GET['estado']) ? $_GET['estado'] : 'activo';
	renderListadoEntidades($conn, $estado);
?>