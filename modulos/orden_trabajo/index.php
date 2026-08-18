<?php
// archivo: /modulos/orden_trabajo/index.php

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
//  COMPONENTES: HEAD + HEADER ERP
// ============================================================
include __DIR__ . '/componentes/head.php';
include __DIR__ . '/componentes/header.php';

// ============================================================
//  CONTROLADOR DEL LISTADO
// ============================================================
require_once __DIR__ . '/controllers/ListController.php';

// ============================================================
//  RENDERIZAR VISTA PRINCIPAL (list.php)
// ============================================================
cargarListado($conn);

// ============================================================
//  SCRIPTS DEL MÓDULO (único archivo de scripts)
// ============================================================
include __DIR__ . '/componentes/scripts_listado.php';

// ============================================================
//  FOOTER ERP
// ============================================================
include __DIR__ . '/componentes/footer.php';
