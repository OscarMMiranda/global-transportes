<?php
// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/index.php
//  RESPONSABILIDAD: Punto de entrada del módulo OT
// ======================================================

// --- Cargar configuración corporativa ---
require_once __DIR__ . '/../../includes/config.php';

// --- Validar conexión corporativa ---
$conn = getConnection();
if (!$conn) {

    // Log corporativo
    error_log("[ERP] Error de conexión en módulo OT: " . date('Y-m-d H:i:s'));

    // Mensaje corporativo
    echo "<div style='padding:20px; color:#b00; font-weight:bold;'>
            ❌ Error de conexión al sistema. Contacte a TI.
          </div>";
    exit;
}

// ======================================================
//  CONTROLADOR PRINCIPAL DEL MÓDULO
//  Este archivo carga la vista list.php
// ======================================================
require_once __DIR__ . '/controllers/ListController.php';
