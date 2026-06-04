<?php

	//	archivo: /modulos/orden_trabajo/index.php
	
// ============================================================
//  CONFIGURACIÓN DE ERRORES
// ============================================================
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// ============================================================
//  CARGAR CONFIG Y CONEXIÓN
// ============================================================
require_once __DIR__ . '/../../includes/config.php';

$conn = getConnection();

if (!$conn) {
    die("❌ Error de conexión a la base de datos");
}

// ============================================================
//  CONTROLADOR DEL LISTADO
// ============================================================
require_once __DIR__ . '/controllers/ListController.php';

// Ejecutar controlador (renderiza la vista)
// cargarListado($conn);
