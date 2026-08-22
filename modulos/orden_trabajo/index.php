<?php
// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/index.php
//  MÓDULO: ÓRDENES DE TRABAJO
//  ARCHIVO: index.php
//  RESPONSABILIDAD: Punto de entrada del módulo
// ======================================================

// --- Cargar configuración y conexión corporativa ---
require_once __DIR__ . '/../../includes/config.php';

$conn = getConnection();
if (!$conn) {
    die("❌ Error de conexión a la base de datos");
}

// ======================================================
//  CONTROLADOR PRINCIPAL (procedural)
//  Este archivo procesa AJAX y carga la vista
// ======================================================
require_once __DIR__ . '/controllers/ListController.php';

// ======================================================
//  COMPONENTES CORPORATIVOS
// ======================================================
include __DIR__ . "/componentes/head.php";
include __DIR__ . "/componentes/header.php";

// ======================================================
//  La vista ya fue cargada por ListController.php
// ======================================================

// ======================================================
//  FOOTER + SCRIPTS
// ======================================================
include __DIR__ . "/componentes/footer.php";
include __DIR__ . "/componentes/scripts.php";
?>
