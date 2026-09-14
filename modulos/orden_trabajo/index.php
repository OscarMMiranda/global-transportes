<?php
// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/index.php
//  RESPONSABILIDAD: Punto de entrada del módulo
// ======================================================

// --- Cargar configuración corporativa ---
require_once __DIR__ . '/../../includes/config.php';

$conn = getConnection();
if (!$conn) {
    die("❌ Error de conexión a la base de datos");
}

// ======================================================
//  CONTROLADOR PRINCIPAL
//  Este archivo se encarga de cargar la vista (list.php)
// ======================================================
require_once __DIR__ . '/controllers/ListController.php';
