<?php
// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/index.php — ACTUALIZADO 28-09-2026
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Punto de entrada del módulo
//  GLOBAL 2026 — Layout Corporativo
// ======================================================

// ------------------------------
// Validación de sesión
// ------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /login.php");
    exit;
}

// ------------------------------
// Cargar configuración global
// ------------------------------
require_once __DIR__ . '/../../includes/config.php';

// ------------------------------
// Validar conexión a la base de datos
// ------------------------------
$conn = getConnection();
if (!$conn) {

    error_log("[GLOBAL ERP] Error de conexión en módulo OT: " . date('Y-m-d H:i:s'));

    echo "<div style='padding:20px; color:#b00; font-weight:bold;'>
            ❌ Error de conexión al sistema. Contacte a TI.
          </div>";
    exit;
}

// ------------------------------
// Validación de permisos (si aplica)
// ------------------------------
if (isset($_SESSION['permisos']) && !in_array('OT_ACCESO', $_SESSION['permisos'])) {
    header("Location: /403.php");
    exit;
}

// ------------------------------
// Cargar controlador del módulo
// ------------------------------
require_once __DIR__ . '/controllers/OrdenTrabajoController.php';
$controller = new OrdenTrabajoController($conn);

// ------------------------------
// Obtener data del controlador
// ------------------------------
$data = $controller->listado();

// ------------------------------
// Cargar layout corporativo
// ------------------------------
include __DIR__ . "/componentes/shared/head_ot_component.php";

include __DIR__ . "/componentes/shared/header_ot_component.php";

include __DIR__ . "/componentes/shared/overlay_ot_component.php";


// ------------------------------
// Cargar TODOS los modales
// ------------------------------
include __DIR__ . "/views/modals_ot.php";


// ------------------------------
// Vista principal
// ------------------------------
include __DIR__ . "/views/listado_ot_view.php";


// ------------------------------
// Footer + scripts
// ------------------------------
include __DIR__ . "/componentes/shared/footer_ot_component.php";

include __DIR__ . "/componentes/shared/scripts_ot_component.php";