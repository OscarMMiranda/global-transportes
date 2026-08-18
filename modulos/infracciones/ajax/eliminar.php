<?php
// archivo: /modulos/infracciones/ajax/eliminar.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// ============================================================
// VALIDAR SESIÓN
// ============================================================
if (!isset($_SESSION['usuario'])) {
    echo json_encode([
        'ok' => false,
        'msg' => 'Sesión expirada'
    ]);
    exit;
}

// ============================================================
// CARGAR CONFIGURACIÓN Y CONTROLADOR
// ============================================================
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../controllers/InfraccionesController.php';

$controller = new InfraccionesController($GLOBALS['db']);

// ============================================================
// VALIDAR ID
// ============================================================
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    echo json_encode(["ok" => false, "msg" => "ID inválido"]);
    exit;
}

// ============================================================
// VALIDAR SI TIENE PAPELETAS ASOCIADAS
// ============================================================
if ($controller->tienePapeletas($id)) {
    echo json_encode([
        "ok" => false,
        "msg" => "No se puede eliminar. La infracción está asociada a papeletas."
    ]);
    exit;
}

// ============================================================
// SOFT DELETE (DESACTIVAR)
// ============================================================
$ok = $controller->desactivar($id);

echo json_encode([
    "ok" => $ok,
    "msg" => $ok ? "Infracción desactivada correctamente" : "No se pudo desactivar la infracción"
]);
exit;
