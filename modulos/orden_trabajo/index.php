<?php
// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/index.php
//  MÓDULO: ÓRDENES DE TRABAJO
//  RESPONSABILIDAD: Punto de entrada del módulo
// ======================================================

// --- Cargar configuración y conexión corporativa ---
require_once __DIR__ . '/../../includes/config.php';

$conn = getConnection();
if (!$conn) {
    die("❌ Error de conexión a la base de datos");
}

// ======================================================
//  COMPONENTES CORPORATIVOS (SIEMPRE ARRIBA)
// ======================================================
include __DIR__ . "/componentes/head.php";
include __DIR__ . "/componentes/header.php";

// ======================================================
//  CONTROLADOR PRINCIPAL
//  Este archivo imprime la vista (list.php)
// ======================================================
require_once __DIR__ . '/controllers/ListController.php';



// ======================================================
//  FOOTER + SCRIPTS (SIEMPRE AL FINAL)
// ======================================================
include __DIR__ . "/componentes/footer.php";
include __DIR__ . "/componentes/scripts.php";
?>
