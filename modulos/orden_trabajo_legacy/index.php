<?php
// archivo: /modulos/orden_trabajo/index.php

// ============================================================
//  CARGAR CONFIG Y CONEXIÓN
// ============================================================
require_once __DIR__ . '/../../includes/config.php';

$conn = getConnection();
if (!$conn) {
    die("❌ Error de conexión a la base de datos");
}

// ============================================================
//  RENDERIZAR VISTA PRINCIPAL (list.php)
// ============================================================
//  IMPORTANTE:
//  - Aquí NO se carga head.php
//  - Aquí NO se carga header.php
//  - Aquí NO se carga footer.php
//  - Aquí NO se carga scripts_listado.php
//  - Aquí NO se carga ListController.php
//
//  Todo eso lo carga la vista list.php.
//  El controlador SOLO debe ejecutarse cuando AJAX lo llama.
// ============================================================

require_once __DIR__ . '/views/list.php';
